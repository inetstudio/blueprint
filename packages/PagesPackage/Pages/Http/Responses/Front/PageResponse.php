<?php

namespace Packages\PagesPackage\Pages\Http\Responses\Front;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;

/**
 * Class PageResponse.
 */
class PageResponse implements Responsable
{
    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $data;

    /**
     * IndexResponse constructor.
     *
     * @param  string  $view
     * @param  array  $data
     */
    public function __construct(string $view, array $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Возвращаем ответ при открытии главной страницы.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function toResponse($request)
    {
        return view('front.modules.pages.'.$this->view, $this->data);
    }
}
