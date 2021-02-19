<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = "jobs";
  
    protected $guarded = [];
    
    public function scopeFilter($query, $filters)
    {
        $filters->apply($query);
    }
}
