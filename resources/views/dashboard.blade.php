@extends('layouts.app')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- サイドバー（メンバー一覧・管理者管理） -->
        <div class="col-md-3 col-lg-2 mb-3">
            <div class="card mb-3">
                <div class="card-header">メンバー一覧</div>
                <div class="card-body p-2">
                    <!-- PC/タブレット: テーブル用リスト -->
                    <div class="d-none d-md-block">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ !request('user_id') ? 'active' : '' }}">全メンバー</a>
                            @foreach($users as $user)
                                <a href="{{ route('dashboard', ['user_id' => $user->id]) }}" class="list-group-item list-group-item-action {{ request('user_id') == $user->id ? 'active' : '' }}">
                                    {{ $user->name }}
                                    <span class="badge bg-secondary float-end">{{ $user->reports_count ?? 0 }}</span>
                                    @if($user->role === 'admin')<span class="badge bg-danger ms-1">管理者</span>@endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- スマホ: ボタン縦並び -->
                    <div class="d-block d-md-none">
                        <button class="btn btn-secondary w-100 mb-2 member-btn" data-user="all">全メンバー</button>
                        @foreach($users as $user)
                            <button class="btn btn-outline-primary w-100 mb-2 member-btn" data-user="{{ $user->id }}">
                                {{ $user->name }}
                                <span class="badge bg-secondary ms-2">{{ $user->reports_count ?? 0 }}</span>
                                @if($user->role === 'admin')<span class="badge bg-danger ms-1">管理者</span>@endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- 管理者管理・クイック操作は省略（省略しない場合は同様にd-none d-md-blockで切替） -->
        </div>
        <!-- メインコンテンツ -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="mb-1 fs-4">
                        @if(request('user_id'))
                            {{ $selectedUser->name }}さんのレポート
                        @else
                            ダッシュボード
                        @endif
                    </h2>
                    <div class="text-muted small">
                        @if(request('user_id'))
                            {{ $selectedUser->email }}
                            @if($selectedUser->role === 'admin')<span class="badge bg-danger ms-2">管理者</span>@endif
                        @else
                            全メンバーのレポート一覧
                        @endif
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6">{{ $reports->count() }}件</span>
                </div>
            </div>
            <!-- 統計カード -->
            <div class="row mb-3 g-2">
                <div class="col-6 col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">総レポート数</div>
                            <div class="fs-4">{{ $reports->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">今日のレポート</div>
                            <div class="fs-4">{{ $reports->filter(fn($r)=>\Carbon\Carbon::parse($r->created_at)->isToday())->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">今月のレポート</div>
                            <div class="fs-4">{{ $reports->filter(fn($r)=>\Carbon\Carbon::parse($r->created_at)->isCurrentMonth())->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">画像付き</div>
                            <div class="fs-4">{{ $reports->filter(fn($r)=>!empty($r->images))->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- レポート一覧テーブル（PC/タブレット） -->
            <div class="d-none d-md-block">
                <div class="card">
                    <div class="card-header">レポート一覧</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>送信者</th>
                                        <th>会社名</th>
                                        <th>工事分類</th>
                                        <th>作業分類</th>
                                        <th>訪問ステータス</th>
                                        <th>画像</th>
                                        <th>署名</th>
                                        <th>作成日時</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports->sortByDesc('created_at') as $report)
                                        <tr>
                                            <td><span class="badge bg-secondary">#{{ $report->id }}</span></td>
                                            <td>
                                                <div>{{ $report->user->name ?? '不明' }}</div>
                                                <div class="small text-muted">{{ $report->user->email ?? '' }}</div>
                                            </td>
                                            <td>{{ $report->company }}</td>
                                            <td>{{ $report->work_type }}</td>
                                            <td>{{ $report->task_type }}</td>
                                            <td>{{ $report->visit_status }}</td>
                                            <td>
                                                @if($report->images && is_array($report->images))
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach(array_slice($report->images, 0, 2) as $img)
                                                            <a href="{{ Storage::url($img) }}" target="_blank" class="btn btn-sm btn-outline-primary">画像</a>
                                                        @endforeach
                                                        @if(count($report->images) > 2)
                                                            <span class="badge bg-secondary">+{{ count($report->images) - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">なし</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->signature)
                                                    <a href="{{ Storage::url($report->signature) }}" target="_blank" class="btn btn-sm btn-outline-success">署名</a>
                                                @else
                                                    <span class="text-muted">なし</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $report->created_at->format('Y-m-d H:i') }}</small></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('editReport', $report->id) }}" class="btn btn-outline-primary">編集</a>
                                                    @if(auth()->user()->role === 'admin')
                                                        <form action="{{ route('deleteReport', $report->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- スマホ: メンバー名ボタン押下でレポート表示 -->
            <div class="d-block d-md-none mt-3" id="mobile-report-list">
                <!-- JSで動的に挿入 -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// スマホ用: メンバー名ボタン押下でレポート表示
const allReports = @json($reports->sortByDesc('created_at')->values());
const users = @json($users->keyBy('id'));
const mobileReportList = document.getElementById('mobile-report-list');

function renderMobileReports(userId) {
    let reports = allReports;
    if (userId && userId !== 'all') {
        reports = reports.filter(r => r.user_id == userId);
    }
    let html = '';
    if (reports.length === 0) {
        html = '<div class="text-center py-4 text-muted">レポートがありません。</div>';
    } else {
        html += '<div class="list-group">';
        reports.forEach(r => {
            html += `<div class='list-group-item mb-2'>
                <div class='fw-bold mb-1'>${r.company} <span class='badge bg-secondary'>#${r.id}</span></div>
                <div class='small text-muted mb-1'>${users[r.user_id]?.name || '不明'} / ${r.work_type} / ${r.task_type}</div>
                <div class='mb-1'>${r.visit_status} / ${r.created_at.substring(0,16).replace('T',' ')}</div>
                <div class='d-flex flex-wrap gap-1 mb-1'>
                    ${(r.images && r.images.length) ? r.images.slice(0,2).map(img => `<a href='/storage/${img}' target='_blank' class='btn btn-sm btn-outline-primary'>画像</a>`).join('') : '<span class="text-muted">画像なし</span>'}
                    ${r.signature ? `<a href='/storage/${r.signature}' target='_blank' class='btn btn-sm btn-outline-success'>署名</a>` : '<span class="text-muted">署名なし</span>'}
                </div>
                <a href='/report/${r.id}/edit' class='btn btn-sm btn-outline-primary'>編集</a>
            </div>`;
        });
        html += '</div>';
    }
    mobileReportList.innerHTML = html;
}

document.querySelectorAll('.member-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.member-btn').forEach(b => b.classList.remove('btn-primary'));
        this.classList.add('btn-primary');
        renderMobileReports(this.dataset.user);
    });
});
// 初期表示: 全メンバー
if(window.innerWidth < 768) renderMobileReports('all');
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.card {
    border-radius: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 0.75em;
}

.form-select-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endpush 