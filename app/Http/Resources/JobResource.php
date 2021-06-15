<?php

namespace App\Http\Resources;
use App\Models\SavedJob;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userId' => $this->client_user_id,
            'title' => $this->title,
            'salary' => $this->salary,
            'companyName' => $this->company_name,
            'photo' => (getFileUrlFromAkoneyaMedia($this->photo)) ?? "",
            'location' => trans('zones.zoneSelections.'.$this->zone_id.'.name'),
            'isSaved' => $this->isSaved,
            'createdAt' =>  strTo24hrFormat($this->created_at)

        ];
    }
}
