<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class JobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'companyName' => 'required|max:3000',
            'jobType' => 'required',
            'location' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'gender' => 'required',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json([
            'result' => null,
            'statusCode' => config('http_status.badRequest'),
            'message' => $errors->first(),
        ],config('http_status.badRequest')));
    }
}
