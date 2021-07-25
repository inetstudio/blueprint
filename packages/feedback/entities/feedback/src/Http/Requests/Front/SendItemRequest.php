<?php

namespace Packages\FeedbackPackage\Feedback\Http\Requests\Front;

use InetStudio\CaptchaPackage\Captcha\Validation\Rules\CaptchaRule;
use InetStudio\FeedbackPackage\Feedback\Http\Requests\Front\SendItemRequest as PackageSendItemRequest;

final class SendItemRequest extends PackageSendItemRequest
{
    public function rules(): array
    {
        $rules = [
            'message' => 'required',
            'g-recaptcha-response' => [
                'required',
                new CaptchaRule,
            ],
        ];

        if (! auth()->user()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:255',
                'email' => 'required|max:255|email',
            ]);
        }

        return $rules;
    }
}
