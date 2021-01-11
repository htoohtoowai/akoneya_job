<?php

namespace App\Filters;

class JobFilter extends Filter
{
    protected $filters = ['title', 'location','jobType'];

    public function title($value)
    {
        return $this->builder->where('title', 'LIKE', "%{$value}%");
    }

    public function location($value)
    {
        return $this->builder->where('zone_id', $value);
    }

    public function jobType($value)
    {
        return $this->builder->where('job_type', $value);
    }
}
