<?php

    namespace App\Http\Requests\Admin;

    use Illuminate\Foundation\Http\FormRequest;

    class AdminUpdateRequest extends FormRequest
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
            $adminId = $this->route('id'); // Lấy ID từ route

            return [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:admins,email,' . $adminId,

                'password' => [
                    'nullable', // Không bắt buộc
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Z])(?=.*[\W_]).+$/'
                ],

                'password_confirmation' => [
                    'required_with:password',
                    'string',
                    'min:8',
                ],
            ];
        }

        /**
         * Get custom messages for validator errors.
         */
        public function messages(): array
        {
            return [
                'email.email' => 'Email không đúng định dạng',
                'email.unique' => 'Email đã tồn tại',

                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp',
                'password.regex' => 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt và 1 chữ hoa',

                'password_confirmation.required_with' => 'Bạn cần nhập lại mật khẩu xác nhận',
            ];
        }
    }
