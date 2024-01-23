<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

use App\Models\Code;
use App\Models\Enterprise;

class ApiCodeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show($category, $code, $language)
    {
        # get the code
        $code = Code::where('Category', $category)->where('Code', $code)->where('Language', $language)->firstOrFail();

        # return the data
        return $code;
    }

    public function insights($category, $code, $language)
    {
        # get the code
        $code = Code::where('Category', $category)->where('Code', $code)->where('Language', $language)->firstOrFail();

        $top_20 = Enterprise::whereHas('subsidies')->inRandomOrder()->limit(20)->get();

        return [
            'code' => $code,
            'enterprises_total' => 92837,
            'enterprises_subsidized' => 345,
            'subsidies_total' => 9990987,
            'top_20' => $top_20
        ];
    }
}
