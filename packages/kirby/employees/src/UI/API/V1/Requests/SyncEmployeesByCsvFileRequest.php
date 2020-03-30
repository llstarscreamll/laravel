<?php

namespace Kirby\Employees\UI\API\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SyncEmployeesByCsvFileRequest.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class SyncEmployeesByCsvFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('employees.sync-by-csv-file');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ];
    }
}