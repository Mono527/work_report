@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">依頼編集</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('updateReport', $report->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">対応会社名</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $report->company) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">担当者名</label>
                            <input type="text" name="person" class="form-control" value="{{ old('person', $report->person) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">工事分類</label>
                            <input type="text" name="work_type" class="form-control" value="{{ old('work_type', $report->work_type) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">作業分類</label>
                            <input type="text" name="task_type" class="form-control" value="{{ old('task_type', $report->task_type) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">作業開始時間</label>
                            <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', $report->start_time) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">作業終了時間</label>
                            <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', $report->end_time) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">依頼内容</label>
                            <textarea name="request_detail" class="form-control" rows="2">{{ old('request_detail', $report->request_detail) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">更新</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 