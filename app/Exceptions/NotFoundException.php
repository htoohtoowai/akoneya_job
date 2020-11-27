<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'result' => null,
            'statusCode' => config('http_status.badRequest'),
            'message'=> $this->getMessage()
        ], config('http_status.badRequest'));
    }
}
