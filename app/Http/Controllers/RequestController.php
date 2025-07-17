<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Report;
use App\Models\User;
use App\Mail\ReportSubmitted;

class RequestController extends Controller
{
    public function showForm()
    {
        return view('request_form');
    }

    public function submitForm(Request $request)
    {
        // Validation
        $request->validate([
            'company' => 'required|string|max:255',
            'person' => 'required|string|max:255',
            'work_type' => 'required|string',
            'task_type' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'visit_status' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['_token', 'images', 'signature']);
        $data['user_id'] = auth()->id(); // Associate with current user

        // Save images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        // Save signature
        if ($request->signature) {
            $signatureData = $request->signature;
            $signaturePath = 'reports/signature_' . time() . '.png';
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $signatureData = base64_decode($signatureData);
            \Storage::disk('public')->put($signaturePath, $signatureData);
            $data['signature'] = $signaturePath;
        }

        $report = Report::create($data);

        $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new ReportSubmitted($report));
        }

        return back()->with('success', '送信が完了しました。');
    }

    public function indexReport()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('report.index', compact('reports'));
    }

    public function editReport($id)
    {
        $report = Report::findOrFail($id);
        return view('report.edit', compact('report'));
    }

    public function updateReport(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->fill($request->except(['_token']))->save();
        return redirect()->route('indexReport')->with('success', '更新しました');
    }

    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);
        
        // Only admins can delete
        if (auth()->user()->role === 'admin') {
            $report->delete();
            return redirect()->route('indexReport')->with('success', '削除しました');
        }
        
        return redirect()->route('indexReport')->with('error', '権限がありません');
    }

    public function dashboard(Request $request)
    {
        // Get all users with their report counts
        $users = User::withCount('reports')->get();
        
        // Get reports based on user filter
        $reportsQuery = Report::with('user')->orderBy('created_at', 'desc');
        
        if ($request->has('user_id') && $request->user_id) {
            $reportsQuery->where('user_id', $request->user_id);
            $selectedUser = User::find($request->user_id);
        } else {
            $selectedUser = null;
        }
        
        $reports = $reportsQuery->get();
        
        return view('dashboard', compact('reports', 'users', 'selectedUser'));
    }

    public function userDashboard()
    {
        $userId = auth()->id();
        
        // Get today's reports
        $todayReports = Report::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get this month's reports
        $monthReports = Report::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();
        
        // Get total reports
        $totalReports = Report::where('user_id', $userId)->get();
        
        // Get recent reports (last 7 days)
        $recentReports = Report::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user_dashboard', compact('todayReports', 'monthReports', 'totalReports', 'recentReports'));
    }

    public function editOwner()
    {
        $user = auth()->user();
        return view('owner.edit', compact('user'));
    }

    public function updateOwner(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return redirect()->route('userDashboard')->with('success', 'オーナー情報を更新しました。');
    }

    public function makeUserAdmin(Request $request)
    {
        // Only admins can make other users admin
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', '権限がありません');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->role = 'admin';
        $user->save();

        return back()->with('success', "ユーザー '{$user->name}' を管理者にしました。");
    }

    public function removeUserAdmin(Request $request)
    {
        // Only admins can remove admin status
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', '権限がありません');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Prevent removing the default admin
        if ($user->email === 'zumado.jp0527@gmail.com') {
            return back()->with('error', 'デフォルト管理者は削除できません。');
        }

        $user->role = 'user';
        $user->save();

        return back()->with('success', "ユーザー '{$user->name}' の管理者権限を削除しました。");
    }
} 