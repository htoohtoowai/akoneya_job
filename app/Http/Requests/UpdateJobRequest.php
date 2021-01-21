<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateJobRequest extends FormRequest
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
            'title' => 'required|max:100',
            'companyName' => 'required|max:100',
            'jobType' => 'required',
            'location' => 'required',
            'salary' => 'required|max:100',
            'address' => 'required|max:200',
            'phone' => 'required|max:50',
            'email' => 'max:50',
            'gender' => 'required',
            'photo' => config("media.file.mimetypes").'|'.config("media.file.sizeLimit"),
            'description' => 'max:500',
            'requirement' => 'max:500'
        ];
    }

    public function messages()
    {
        return [
            'photo.mimes' => trans('message.invalidFileType'),
            'photo.uploaded' => trans('message.invalidFileSize')
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json([
            'result' => null,
            'statusCode' => config('http_status.badRequest'),
            'message' => $errors->first(),
        ], config('http_status.badRequest')));
    }
}
