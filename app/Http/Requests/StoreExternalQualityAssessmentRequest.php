<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExternalQualityAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'time' => 'required',
            'provider' => 'required',
            'content' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'time.required' => 'Vui lòng chọn thời gian ngoại kiểm',
            'provider.required' => 'Vui lòng nhập đơn vị thực hiện',
            'content.required' => 'Vui lòng nhập nội dung ngoại kiểm',
        ];
    }
}
