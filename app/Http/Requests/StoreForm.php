<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreForm extends FormRequest
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
        Log::info($this);
        return [
            'title' => "required",
            'questions' => "required|array",
            'questions.*.text' => "required",
            'questions.*.type' => "required",
            'questions.*.answers' => "required_if:questions.*.type, multiple_choice|required_if:questions.*.type, checkbox",
            'questions.*.answers.*.text' => "required_with:questions.*.answers"
        ];
    }
}
