<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateArticleRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'title' => ['required', 'min:3', 'max:255','unique:articles,title,'.$request->article->id],
            'content' => ['required','min:5'],
            'image' => ['image'],
            'category' => ['required','exists:categories,id'],
        ];
    }
}
