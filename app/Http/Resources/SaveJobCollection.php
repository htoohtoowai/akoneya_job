<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaveJobCollection extends ResourceCollection
{
    private $pagination;
    private $nextId;

    public function __construct($resource, $nextId)
    {
        $this->nextId = $nextId;
    
        $this->pagination = [
            'nextId' => $this->nextId
        ];

        parent::__construct($resource);
    }

    
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->pagination
        ];
    }
}
