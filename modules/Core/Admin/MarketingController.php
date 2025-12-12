<?php

namespace Modules\Core\Admin;

use Modules\AdminController;

class MarketingController extends AdminController
{
    public function index()
    {
        $data = [
            'page_title' => __('Marketing Messages')
        ];

        return view('Core::admin.marketing.index', $data);
    }
}
