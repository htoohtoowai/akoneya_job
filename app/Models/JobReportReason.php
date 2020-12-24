<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobReportReason extends Model
{
    protected $table = "jobs_report_reasons";
  
    protected $guarded = [];
    
    public $timestamps = false;
}
