<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'company', 'person', 'site', 'store', 'work_type', 'task_type', 'request_detail',
        'start_time', 'end_time', 'visit_status', 'repair_place', 'visit_status_detail',
        'work_detail', 'signature', 'images', 'user_id'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'images' => 'array',
    ];

    /**
     * Get the user who created this report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
