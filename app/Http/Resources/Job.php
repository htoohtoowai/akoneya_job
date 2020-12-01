<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Job extends JsonResource
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
            'title' => $this->title,
            'salary' => $this->salary,
            'company' => $this->company,
            'location' => config('zones.zoneSelections')[$this->zone_id]['name'],
            'isSaved' => true,
            'createdAt' => strTo24hrFormat($this->created_at)
        ];
    }
}
