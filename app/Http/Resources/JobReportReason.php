<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobReportReason extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => changeLanguage($request, $this->name, $this->name_mm, $this->name_zh)
        ];
    }
}
