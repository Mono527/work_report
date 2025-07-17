@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">依頼一覧</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>会社名</th>
                                    <th>担当者</th>
                                    <th>工事分類</th>
                                    <th>作業分類</th>
                                    <th>開始</th>
                                    <th>終了</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->company }}</td>
                                        <td>{{ $report->person }}</td>
                                        <td>{{ $report->work_type }}</td>
                                        <td>{{ $report->task_type }}</td>
                                        <td>{{ $report->start_time }}</td>
                                        <td>{{ $report->end_time }}</td>
                                        <td>
                                            <a href="{{ route('editReport', $report->id) }}" class="btn btn-sm btn-outline-primary">編集</a>
                                            @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('deleteReport', $report->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">依頼がありません。</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 