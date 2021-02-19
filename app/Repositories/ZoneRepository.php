<?php
namespace App\Repositories;

use App\Models\Job;
use App\Repositories\ResponseRepository;
use App\Http\Resources\ZoneListResource;

class ZoneRepository extends BaseRepository
{
    public function __construct(Job $model, ResponseRepository $responseRepo)
    {
        $this->model = $model;
        $this->responseRepo = $responseRepo;
		$this->zones = config('zones.zoneSelections');

    }
    public function getZones()
    {
        $data['locations'] = ZoneListResource::collection($this->zones)->sortBy('name')->values();

        return $this->responseRepo->successResponse($data, $this->responseRepo->successStatusCode, trans('message.successMsg'));
    }
}
