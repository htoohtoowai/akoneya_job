<?php
namespace App\Repositories;

class ResponseRepository
{
    public function __construct()
    {
        $this->createStatusCode = config('http_status.created');
        $this->successStatusCode = config('http_status.success');
        $this->badRequestStatusCode = config('http_status.badRequest');
        $this->notFoundStatusCode = config('http_status.badRequest');
        $this->unauthorizedStatusCode = config('http_status.unauthorized');
        $this->successMsg = trans('message.successMsg');
        $this->createSuccessMsg = trans('message.createSuccessMsg');
        $this->updateSuccessMsg = trans('message.updateSuccessMsg');
        $this->deletedsuccessMsg = trans('message.deleteSuccessMsg');
        $this->alreadyRegister = trans('message.alreadyRegister');
        $this->notFoundMsg = trans('message.notFoundMsg');
        $this->unauthorisedMsg = trans('message.notFoundMsg');
        $this->badRequestMsg = trans('message.badRequestMsg');
        $this->overNewsfeedMediaUploadLimit = trans('message.overNewsfeedMediaUploadLimit');
    }
    public function jsonResponse($data, $statusCode, $message)
    {
        return response()->json([
            'result' => $data,
            'statusCode' => $statusCode,
            'message'=> $message
        ], $statusCode);
    }

    public function successResponse($data, $successStatusCode, $message)
    {
        return $this->jsonResponse($data, $successStatusCode, $message);
    }
    
    public function failResponse($data, $failStatusCode, $message)
    {
        return $this->jsonResponse($data, $failStatusCode, $message);
    }
}
