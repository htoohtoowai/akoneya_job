<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaveJob extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'saveJobId' => $this->saveJobId,
            'jobId' => $this->jobId,
            'photo' => $this->photo ? getFileUrlFromAkoneyaMedia($this->photo) : "",
            'title' => $this->title,
            'createdAt' => strTo24hrFormat($this->created_at),
            'zone' => config('zones.zoneSelections.'.$this->zone_id.'.name') ,
            'salary' =>$this->salary,
            'companyName' => $this->company_name
        ];
    }
}
