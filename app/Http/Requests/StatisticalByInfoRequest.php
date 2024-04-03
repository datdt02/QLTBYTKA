<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatisticalByInfoRequest extends FormRequest
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
            'dateFailure' => 'date_format:Y-m-d',
            'keyword' => 'string',
            'categoryId' => 'int',
            'deviceId' => 'int',
            'status' => 'string',
            'risk' => 'string',
            'yearManufacture' => 'int',
            'yearUse' => 'int',
            'bidProjectId' => 'int',
            'accrediationDate'=> 'date_format:Y-m-d',
            'departmentId' => 'int',
            'startDate' => 'date_format:Y-m-d',
            'endDate' => 'date_format:Y-m-d',
        ];
    }
}
