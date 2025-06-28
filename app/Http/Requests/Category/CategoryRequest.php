<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category'); // Lấy ID từ route parameter

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($categoryId) {
                    // Không cho phép set parent_id là chính nó
                    if ($value == $categoryId) {
                        $fail('Danh mục không thể là parent của chính nó.');
                    }
                },
            ],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục là bắt buộc',
            'name.string' => 'Tên danh mục phải là chuỗi',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
            'name.unique' => 'Tên danh mục đã tồn tại',

            'slug.required' => 'Slug là bắt buộc',
            'slug.string' => 'Slug phải là chuỗi',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',
            'slug.regex' => 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang',
            'slug.unique' => 'Slug đã tồn tại',

            'parent_id.integer' => 'ID danh mục cha phải là số nguyên',
            'parent_id.exists' => 'Danh mục cha không tồn tại',

            'meta_title.string' => 'Meta title phải là chuỗi',
            'meta_title.max' => 'Meta title không được vượt quá 255 ký tự',

            'meta_description.string' => 'Meta description phải là chuỗi',
            'meta_description.max' => 'Meta description không được vượt quá 500 ký tự',

            'meta_keywords.string' => 'Meta keywords phải là chuỗi',
            'meta_keywords.max' => 'Meta keywords không được vượt quá 500 ký tự',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'tên danh mục',
            'slug' => 'slug',
            'description' => 'mô tả',
            'parent_id' => 'danh mục cha',
            'status' => 'trạng thái',
            'sort_order' => 'thứ tự sắp xếp',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
        ];
    }
}