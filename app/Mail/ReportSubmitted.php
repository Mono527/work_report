<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;

class ReportSubmitted extends Mailable
{
    use SerializesModels;

    public $report;
    public $imageCids = [];
    public $signatureCid = null;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function build()
    {
        return $this->subject('📋 新しいレポートが送信されました - ' . $this->report->company)
            ->view('emails.report_submitted')
            ->with([
                'report' => $this->report,
            ]);
    }
}
