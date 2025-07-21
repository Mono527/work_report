<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しいレポートが送信されました</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        .field {
            margin-bottom: 12px;
        }
        .field-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .field-value {
            color: #6c757d;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #4e73df;
        }
        .images-section {
            margin-top: 20px;
        }
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .image-item {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .signature-section {
            margin-top: 20px;
            text-align: center;
        }
        .signature-image {
            max-width: 200px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
        }
        .btn:hover {
            background: linear-gradient(135deg, #224abe, #4e73df);
        }
        .user-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        .timestamp {
            color: #6c757d;
            font-size: 12px;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📋 新しいレポートが送信されました</h1>
        </div>
        
        <div class="content">
            <div class="user-info">
                <strong>送信者:</strong> {{ $report->user->name ?? 'Unknown User' }} ({{ $report->user->email ?? 'No email' }})
                <br>
                <strong>送信日時:</strong> {{ $report->created_at->format('Y年m月d日 H:i') }}
            </div>

            <div class="section">
                <div class="section-title">🏢 基本情報</div>
                <div class="field">
                    <div class="field-label">会社名</div>
                    <div class="field-value">{{ $report->company }}</div>
                </div>
                <div class="field">
                    <div class="field-label">担当者名</div>
                    <div class="field-value">{{ $report->person }}</div>
                </div>
                <div class="field">
                    <div class="field-label">現場・店舗</div>
                    <div class="field-value">{{ $report->site ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">店舗名</div>
                    <div class="field-value">{{ $report->store ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">🔧 作業情報</div>
                <div class="field">
                    <div class="field-label">工事分類</div>
                    <div class="field-value">{{ $report->work_type }}</div>
                </div>
                <div class="field">
                    <div class="field-label">作業分類</div>
                    <div class="field-value">{{ $report->task_type }}</div>
                </div>
                <div class="field">
                    <div class="field-label">依頼内容</div>
                    <div class="field-value">{{ $report->request_detail ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">⏰ 時間情報</div>
                <div class="field">
                    <div class="field-label">作業開始時間</div>
                    <div class="field-value">{{ $report->start_time ? $report->start_time->format('Y年m月d日 H:i') : 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">作業終了時間</div>
                    <div class="field-value">{{ $report->end_time ? $report->end_time->format('Y年m月d日 H:i') : 'N/A' }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">📍 訪問情報</div>
                <div class="field">
                    <div class="field-label">訪問ステータス</div>
                    <div class="field-value">{{ $report->visit_status }}</div>
                </div>
                <div class="field">
                    <div class="field-label">修理場所</div>
                    <div class="field-value">{{ $report->repair_place ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">訪問時状況</div>
                    <div class="field-value">{{ $report->visit_status_detail ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">📝 作業詳細</div>
                <div class="field">
                    <div class="field-label">作業内容</div>
                    <div class="field-value">{{ $report->work_detail ?? 'N/A' }}</div>
                </div>
            </div>

            @php use Illuminate\Support\Facades\Storage; @endphp
            @if($report->images && count($report->images) > 0)
            <div class="section">
                <div class="section-title">📸 添付画像</div>
                <div class="images-section">
                    <div class="image-grid" style="display:flex">
                        @foreach($report->images as $image)
                        <div class="image-item" style="width:40%">
                            <img src="{{ $message->embed(Storage::disk('public')->path($image)) }}" alt="Report Image" style="height: auto; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($report->signature)
            <div class="section">
                <div class="section-title">✍️ 署名</div>
                <div class="signature-section">
                    <img src="{{ $message->embed(Storage::disk('public')->path($report->signature)) }}" alt="Signature" class="signature-image">
                </div>
            </div>
            @endif

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/dashboard') }}" class="btn">管理画面で確認する</a>
            </div>

            <div class="timestamp">
                このメールは自動送信されています。返信はできません。
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} レポート管理システム. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
