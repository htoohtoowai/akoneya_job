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
            'id' => $this->id,
            'jobId' => $this->job_id,
            'photo' => $this->jobInfo->photo ? getFileUrlFromAkoneyaMedia($this->jobInfo->photo) : "",
            'title' => $this->jobInfo->title,
            'createdAt' => strTo24hrFormat($this->jobInfo->created_at),
            'location' => config('zones.zoneSelections.'.$this->jobInfo->zone_id.'.name') ,
            'salary' =>$this->jobInfo->salary,
            'companyName' => $this->jobInfo->company_name
        ];
    }
}
