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
            'title' => 'required|max:100',
            'companyName' => 'required|max:100',
            'jobType' => 'required',
            'location' => 'required',
            'salary' => 'required|max:100',
            'address' => 'required|max:200',
            'phone' => 'required|max:50',
            'email' => 'max:50',
            'gender' => 'required',
            'photo' => 'image|file|max:'.config("filesystems.imageSizeLimit"),
            'description' => 'max:500',
            'requirement' => 'max:500'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => trans('message.jobTitleRequired'),
            'title.max' => trans('message.overJobTitleCharacters'),
            'companyName.required' => trans('message.companyNameRequired'),
            'companyName.max' => trans('message.overCompanyNameCharacters'),
            'jobType.required' => trans('message.jobTypeRequired'),
            'location.required' => trans('message.locationTypeRequired'),
            'salary.required' => trans('message.salaryRequired'),
            'salary.max' => trans('message.overSalaryCharacters'),
            'address.required' => trans('message.addressRequired'),
            'address.max' => trans('message.overAddressCharacters'),
            'phone.required' => trans('message.phoneRequired'),
            'phone.max' => trans('message.overPhoneCharacters'),
            'email.max' => trans('message.overEmailCharacters'),
            'gender.required' => trans('message.genderRequired'),
            'photo.image' => trans('message.invalidFileType'),
            'photo.max' => trans('message.invalidFileSize'),
            'description.max' => trans('message.overDescriptionCharacters'),
            'requirement.max' => trans('message.overRequirementCharacters'),
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
