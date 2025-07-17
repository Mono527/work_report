@extends('layouts.app')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-3">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">ダッシュボードに戻る</a>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="mb-1 fs-4">マイダッシュボード</h2>
                    <div class="text-muted small">{{ auth()->user()->name }}さん、お疲れ様です！</div>
                </div>
                <div class="text-end">
                    <a href="{{ route('showForm') }}" class="btn btn-primary">新規レポート作成</a>
                </div>
            </div>
            <!-- 統計カード -->
            <div class="row mb-3 g-2">
                <div class="col-4">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">今日のレポート</div>
                            <div class="fs-4">{{ $todayReports->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">今月のレポート</div>
                            <div class="fs-4">{{ $monthReports->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card bg-light">
                        <div class="card-body text-center p-3">
                            <div class="fw-bold">総レポート数</div>
                            <div class="fs-4">{{ $totalReports->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- モバイル用: レポート一覧をボタンで表示 -->
            <div class="d-block d-md-none mb-3">
                @foreach($recentReports as $report)
                    <button class="btn btn-outline-primary w-100 text-start mb-2 report-toggle-btn" data-report-id="{{ $report->id }}">
                        {{ $report->created_at->format('m/d') }} のレポート
                    </button>
                    <div class="report-details card mb-2" id="report-details-{{ $report->id }}" style="display:none;">
                        <div class="card-body">
                            <div><strong>会社名:</strong> {{ $report->company }}</div>
                            <div><strong>工事分類:</strong> {{ $report->work_type }}</div>
                            <div><strong>作業分類:</strong> {{ $report->task_type }}</div>
                            <div><strong>訪問ステータス:</strong> {{ $report->visit_status }}</div>
                            <div><strong>作業内容:</strong> {{ $report->work_detail }}</div>
                            <div><strong>画像:</strong>
                                @if($report->images && is_array($report->images))
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach($report->images as $img)
                                            <img src="{{ Storage::url($img) }}" class="img-thumbnail report-img-thumb" style="width:70px;height:70px;object-fit:cover;cursor:zoom-in;" data-fullsrc="{{ Storage::url($img) }}">
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">なし</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- デスクトップ/タブレット用: 既存のテーブル -->
            <div class="d-none d-md-block">
                <!-- 今日のレポート -->
                <div class="card mb-3">
                    <div class="card-header">今日のレポート</div>
                    <div class="card-body p-0">
                        @if($todayReports->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>会社名</th>
                                            <th>工事分類</th>
                                            <th>作業分類</th>
                                            <th>訪問ステータス</th>
                                            <th>画像</th>
                                            <th>作成時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayReports as $report)
                                            <tr>
                                                <td><span class="badge bg-secondary">#{{ $report->id }}</span></td>
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
                                                <td><small>{{ $report->created_at->format('H:i') }}</small></td>
                                                <td>
                                                    <a href="{{ route('editReport', $report->id) }}" class="btn btn-sm btn-outline-primary">編集</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">今日のレポートはありません。</div>
                        @endif
                    </div>
                </div>
                <!-- 最近のレポート（過去7日間） -->
                <div class="card">
                    <div class="card-header">最近のレポート（過去7日間）</div>
                    <div class="card-body p-0">
                        @if($recentReports->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>日付</th>
                                            <th>会社名</th>
                                            <th>工事分類</th>
                                            <th>訪問ステータス</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentReports as $report)
                                            <tr>
                                                <td>{{ $report->created_at->format('m/d') }}</td>
                                                <td>{{ $report->company }}</td>
                                                <td>{{ $report->work_type }}</td>
                                                <td>{{ $report->visit_status }}</td>
                                                <td>
                                                    <a href="{{ route('editReport', $report->id) }}" class="btn btn-sm btn-outline-primary">編集</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">最近のレポートはありません。</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 画像プレビューモーダル -->
<div id="image-modal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">画像プレビュー</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modal-image" src="" alt="Original" style="max-width:100%; max-height:70vh; border-radius:0.7rem; box-shadow:0 0 16px #aaa;">
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle report details on button click
    document.querySelectorAll('.report-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-report-id');
            const details = document.getElementById('report-details-' + id);
            if (details.style.display === 'none') {
                document.querySelectorAll('.report-details').forEach(d => d.style.display = 'none');
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        });
    });
    // Image modal preview
    document.querySelectorAll('.report-img-thumb').forEach(img => {
        img.addEventListener('dblclick', function() {
            const modalImg = document.getElementById('modal-image');
            modalImg.src = this.getAttribute('data-fullsrc');
            const modal = new bootstrap.Modal(document.getElementById('image-modal'));
            modal.show();
        });
    });
    // Clear modal image src when modal is closed
    const imageModal = document.getElementById('image-modal');
    if (imageModal && !imageModal.hasAttribute('data-listener')) {
        imageModal.setAttribute('data-listener', 'true');
        imageModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('modal-image').src = '';
        });
    }
});
</script>
@endpush
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.card {
    border-radius: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush 