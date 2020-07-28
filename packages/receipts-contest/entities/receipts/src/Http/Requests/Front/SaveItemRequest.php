<?php

namespace Packages\ReceiptsContest\Receipts\Http\Requests\Front;

use InetStudio\ReceiptsContest\Receipts\Http\Requests\Front\SendRequest as PackageSaveItemRequest;

final class SaveItemRequest extends PackageSaveItemRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'additional_info.name.required' => 'Поле «Имя» обязательно для заполнения',
            'additional_info.name.max' => 'Поле «Имя» не должно превышать 255 символов',

            'additional_info.phone.required' => 'Поле «Телефон» обязательно для заполнения',

            'check_image.required' => 'Поле «Фотография чека» обязательно для заполнения',
            'check_image.image' => 'Поле «Фотография чека» должно быть изображением',
        ];
    }

    public function rules(): array
    {
        return [
            'additional_info.name' => 'required|max:255',
            'additional_info.phone' => 'required',
            'check_image' => 'required|image',
        ];
    }
}
