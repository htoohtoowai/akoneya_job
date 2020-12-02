<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SaveJobRepository;

class SaveJobController extends Controller
{
    public function __construct(SaveJobRepository $SaveJobRepository)
    {
        $this->SaveJobRepository = $SaveJobRepository;
    }
    public function saveJob(Request $request, $id)
    {
        return $this->SaveJobRepository->saveJob($id, $request->auth_user_id);
    }
}
