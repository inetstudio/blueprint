<?php

namespace Packages\ReceiptsContest\Receipts\Http\Requests\Front;

use InetStudio\CaptchaPackage\Captcha\Validation\Rules\CaptchaRule;
use InetStudio\ReceiptsContest\Receipts\Http\Requests\Front\SearchRequest as PackageSearchRequest;

class SearchRequest extends PackageSearchRequest
{
    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => 'Поле «Капча» обязательно для заполнения',
            'g-recaptcha-response.captcha' => 'Неверный код капча',
        ];
    }

    public function rules(): array
    {
        return ($this->route('type') === 'status')
            ? [
                'g-recaptcha-response' => [
                    'required',
                    new CaptchaRule,
                ],
            ]
            : [];
    }
}
