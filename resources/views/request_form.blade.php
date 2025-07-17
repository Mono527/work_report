@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">ダッシュボードに戻る</a>
            </div>
            <div class="card">
                <div class="card-header">作業報告フォーム<br><span class="small text-white-50">作業内容を正確に記入してください</span></div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('submitForm') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- 基本情報 -->
                        <div class="mb-3">
                            <div class="fw-bold mb-2">基本情報</div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">会社名</label>
                                    <input type="text" name="company" class="form-control" value="{{ old('company') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">担当者名</label>
                                    <input type="text" name="person" class="form-control" value="{{ old('person') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">現場名</label>
                                    <input type="text" name="site" class="form-control" value="{{ old('site') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">店舗名</label>
                                    <input type="text" name="store" class="form-control" value="{{ old('store') }}">
                                </div>
                            </div>
                        </div>
                        <!-- 作業分類 -->
                        <div class="mb-3">
                            <div class="fw-bold mb-2">作業分類</div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">工事分類</label>
                                    <select name="work_type" class="form-select" required>
                                        <option value="">選択してください</option>
                                        <option value="エアコン" {{ old('work_type') == 'エアコン' ? 'selected' : '' }}>エアコン</option>
                                        <option value="ダクト" {{ old('work_type') == 'ダクト' ? 'selected' : '' }}>ダクト</option>
                                        <option value="電気" {{ old('work_type') == '電気' ? 'selected' : '' }}>電気</option>
                                        <option value="その他" {{ old('work_type') == 'その他' ? 'selected' : '' }}>その他</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">作業分類</label>
                                    <select name="task_type" class="form-select" required>
                                        <option value="">選択してください</option>
                                        <option value="点検調査" {{ old('task_type') == '点検調査' ? 'selected' : '' }}>点検調査</option>
                                        <option value="修理" {{ old('task_type') == '修理' ? 'selected' : '' }}>修理</option>
                                        <option value="入替" {{ old('task_type') == '入替' ? 'selected' : '' }}>入替</option>
                                        <option value="納品" {{ old('task_type') == '納品' ? 'selected' : '' }}>納品</option>
                                        <option value="その他" {{ old('task_type') == 'その他' ? 'selected' : '' }}>その他</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">依頼内容</label>
                                <textarea name="request_detail" class="form-control" rows="2">{{ old('request_detail') }}</textarea>
                            </div>
                        </div>
                        <!-- 作業時間 -->
                        <div class="mb-3">
                            <div class="fw-bold mb-2">作業時間</div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">作業開始時間</label>
                                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">作業終了時間</label>
                                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                </div>
                            </div>
                        </div>
                        <!-- 訪問情報 -->
                        <div class="mb-3">
                            <div class="fw-bold mb-2">訪問情報</div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">訪問ステータス</label>
                                    <select name="visit_status" class="form-select" required>
                                        <option value="">選択してください</option>
                                        <option value="見積提出" {{ old('visit_status') == '見積提出' ? 'selected' : '' }}>見積提出</option>
                                        <option value="完了" {{ old('visit_status') == '完了' ? 'selected' : '' }}>完了</option>
                                        <option value="対応不可" {{ old('visit_status') == '対応不可' ? 'selected' : '' }}>対応不可</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">修理場所</label>
                                    <input type="text" name="repair_place" class="form-control" value="{{ old('repair_place') }}">
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">訪問時状況</label>
                                <textarea name="visit_status_detail" class="form-control" rows="2">{{ old('visit_status_detail') }}</textarea>
                            </div>
                        </div>
                        <!-- 作業詳細 -->
                        <div class="mb-3">
                            <div class="fw-bold mb-2">作業詳細</div>
                            <div class="mt-2">
                                <label class="form-label">作業内容</label>
                                <textarea name="work_detail" class="form-control" rows="2">{{ old('work_detail') }}</textarea>
                            </div>
                        </div>
                        <!-- サイン・画像添付 -->
                        <div class="mb-3">
                            <label class="form-label">サイン（指で記入）</label>
                            <div class="mb-2">
                                <canvas id="signature-pad" class="w-100" style="height:180px;"></canvas>
                                <input type="hidden" name="signature" id="signature-input">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary btn-sm" id="clear-signature">クリア</button>
                                    <button type="button" class="btn btn-secondary btn-sm" id="undo-signature">元に戻す</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">画像添付（最大10枚）</label>
                            <div class="image-upload-container">
                                <input type="file" name="images[]" id="image-input" class="form-control" accept="image/*" multiple>
                                <div class="form-text">JPEG, PNG, JPG, GIF形式、最大2MBまで</div>
                                <div id="image-preview-container" class="mt-3" style="display: none;">
                                    <div id="image-preview-grid" class="row g-2"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">送信</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Signature Pad
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16,
            velocityFilterWeight: 0.7,
            penColor: '#000000'
        });

        // Responsive canvas sizing
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            canvas.style.width = rect.width + 'px';
            canvas.style.height = rect.height + 'px';
            
            signaturePad.clear();
        }

        // Initial resize
        resizeCanvas();

        // Resize on window resize
        window.addEventListener('resize', resizeCanvas);

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!signaturePad.isEmpty()) {
                document.getElementById('signature-input').value = signaturePad.toDataURL();
            }
        });

        // Clear button
        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
        });

        // Undo button
        document.getElementById('undo-signature').addEventListener('click', function() {
            const data = signaturePad.toData();
            if (data.length > 0) {
                data.pop();
                signaturePad.fromData(data);
            }
        });

        // Prevent scrolling when drawing on mobile
        canvas.addEventListener('touchstart', function(e) {
            e.preventDefault();
        }, { passive: false });

        canvas.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, { passive: false });

        // 画像プレビュー
        const imageInput = document.getElementById('image-input');
        const previewContainer = document.getElementById('image-preview-container');
        const previewGrid = document.getElementById('image-preview-grid');
        let selectedFiles = [];

        imageInput.addEventListener('change', function(e) {
            selectedFiles = Array.from(e.target.files);
            updateImagePreview();
        });

        function updateImagePreview() {
            previewGrid.innerHTML = '';
            if (selectedFiles.length === 0) {
                previewContainer.style.display = 'none';
                return;
            }
            previewContainer.style.display = 'block';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-3';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview';
                    img.style = 'width:100%;height:90px;object-fit:cover;border-radius:0.7rem;border:1.5px solid #d0e3fa;cursor:zoom-in;';
                    img.setAttribute('data-fullsrc', e.target.result);
                    // Attach double-click event for modal preview
                    img.addEventListener('dblclick', function() {
                        // For debugging: show alert
                        // alert('Double-clicked!');
                        const modalImg = document.getElementById('modal-image');
                        modalImg.src = this.getAttribute('data-fullsrc');
                        const modal = new bootstrap.Modal(document.getElementById('image-modal'));
                        modal.show();
                    });
                    col.innerHTML = `
                        <div class="image-preview-item position-relative mb-2"></div>
                    `;
                    col.querySelector('.image-preview-item').appendChild(img);
                    col.querySelector('.image-preview-item').innerHTML += `
                        <button type="button" class="remove-image btn btn-danger btn-sm position-absolute top-0 start-0 m-1" data-index="${index}" title="削除">×</button>
                        <div class="image-info small mt-1">
                            <div class="image-name">${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}</div>
                            <div class="image-size">${(file.size/1024).toFixed(2)} KB</div>
                        </div>
                    `;
                    previewGrid.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
            setTimeout(() => {
                document.querySelectorAll('.remove-image').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        selectedFiles.splice(idx, 1);
                        updateFileInput();
                        updateImagePreview();
                    });
                });
            }, 100);
            // Ensure modal image src is cleared when modal is closed
            const imageModal = document.getElementById('image-modal');
            if (!imageModal.hasAttribute('data-listener')) {
                imageModal.setAttribute('data-listener', 'true');
                imageModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('modal-image').src = '';
                });
            }
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            imageInput.files = dataTransfer.files;
        }
    });
</script>
@endpush 