<?php

namespace Packages\ReceiptsContest\Receipts\Http\Requests\Front;

use InetStudio\ReceiptsContest\Receipts\Http\Requests\Front\SendRequest as PackageSendRequest;

final class SendRequest extends PackageSendRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'additional_info.name.required' => 'Поле «Имя Фамилия» обязательно для заполнения',
            'additional_info.name.max' => 'Поле «Имя Фамилия» не должно превышать 255 символов',

            'additional_info.city.required' => 'Поле «Город» обязательно для заполнения',
            'additional_info.city.max' => 'Поле «Город» не должно превышать 255 символов',

            'additional_info.phone.required' => 'Поле «Номер мобильного телефона» обязательно для заполнения',
            'additional_info.date.required' => 'Поле «Дата рождения» обязательно для заполнения',
            'additional_info.email.required' => 'Поле «Адрес электронной почты» обязательно для заполнения',

            'receipt_image.required' => 'Поле «Фотография чека» обязательно для заполнения',
            'receipt_image.image' => 'Поле «Фотография чека» должно быть изображением',
        ];
    }

    public function rules(): array
    {
        return [
            'additional_info.name' => 'required|max:255',
            'additional_info.city' => 'required|max:255',
            'additional_info.phone' => 'required',
            'additional_info.date' => 'required',
            'additional_info.email' => 'required',
            'receipt_image' => 'required|image',
        ];
    }
}
