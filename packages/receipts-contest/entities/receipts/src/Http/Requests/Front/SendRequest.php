<?php

namespace Packages\ReceiptsContest\Receipts\Http\Requests\Front;

use InetStudio\CaptchaPackage\Captcha\Validation\Rules\CaptchaRule;
use InetStudio\ReceiptsContest\Receipts\Http\Requests\Front\SendRequest as PackageSendRequest;

final class SendRequest extends PackageSendRequest
{
    public function messages(): array
    {
        return [
            'additional_info.name.required' => 'Поле обязательно для заполнения',
            'additional_info.name.max' => 'Поле не должно превышать 255 символов',

            'additional_info.phone.required' => 'Поле обязательно для заполнения',
            'additional_info.email.required' => 'Поле обязательно для заполнения',

            'receipt_image.required' => 'Поле «Фотография чека» обязательно для заполнения',
            'receipt_image.image' => 'Поле «Фотография чека» должно быть изображением',

            'g-recaptcha-response.required' => 'Поле «Капча» обязательно для заполнения',
            'g-recaptcha-response.captcha' => 'Неверный код капча',
        ];
    }

    public function rules(): array
    {
        return [
            'additional_info.name' => 'required|max:255',
            'additional_info.phone' => 'required',
            'additional_info.email' => 'required',
            'receipt_image' => 'required|image',
            'g-recaptcha-response' => [
                'required',
                new CaptchaRule,
            ],
        ];
    }
}
