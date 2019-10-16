<?php

namespace Packages\ChecksContest\Checks\Http\Requests\Front;

use InetStudio\ChecksContest\Checks\Http\Requests\Front\SaveItemRequest as PackageSaveItemRequest;

/**
 * Class SaveItemRequest.
 */
final class SaveItemRequest extends PackageSaveItemRequest
{
    /**
     * Определить, авторизован ли пользователь для этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
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

    /**
     * Правила проверки запроса.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'additional_info.name' => 'required|max:255',
            'additional_info.phone' => 'required',
            'check_image' => 'required|image',
        ];
    }
}
