<?php

namespace llstarscreamll\Novelties\UI\API\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateNoveltiesToUsersRequest.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class CreateNoveltiesToUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('novelties.create-novelties-to-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_ids.*' => ['numeric'],
            'employee_ids' => ['required', 'array', 'exists:employees,id'],
            'novelties' => ['required', 'array'],
            'novelties.*.novelty_type_id' => ['required', 'numeric', 'exists:novelty_types,id'],
            'novelties.*.start_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'novelties.*.end_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:novelties.*.start_at'],
        ];
    }
}