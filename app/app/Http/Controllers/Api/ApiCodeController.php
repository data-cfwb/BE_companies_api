<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Code;
use App\Models\Enterprise;
use App\Models\Activity;
use App\Models\Subsidy;

use App\Http\Resources\EnterpriseDigestResource;

class ApiCodeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show($category, $code, $language)
    {
        $code = Code::where('Category', $category)->where('Code', $code)->where('Language', $language)->firstOrFail();

        return $code;
    }

    public function insights($category, $code, $language)
    {
        $NaceVersion = 'Nace' . $category;
        # get the code
        $code = Code::where('Category', $NaceVersion)->where('Code', $code)->where('Language', $language)->firstOrFail();

      
        $enterprises_total = Enterprise::whereHas('activities', function ($query) use ($category, $code) {
            $query->where('NaceVersion', $category)->where('NaceCode', $code->Code);
        })->get();
        
        $entreprises_subsidized = Enterprise::whereIn('EnterpriseNumber', $enterprises_total->pluck('EnterpriseNumber'))->whereHas('subsidies')->get();

        // sum of subsidies
        $subsidies_total = Subsidy::whereIn('EnterpriseNumber', $entreprises_subsidized->pluck('EnterpriseNumber'))->sum('AmountInEuros');

        $all_enterprises = Enterprise::whereIn('EnterpriseNumber', $entreprises_subsidized->pluck('EnterpriseNumber'))->inRandomOrder()->get();

        return [
            'code' => $code,
            'enterprises_total' => $enterprises_total->count(),
            'enterprises_subsidized' => $entreprises_subsidized->count(),
            'subsidies_total' => number_format($subsidies_total, 2, ',', ' '),
            'all_enterprises' => EnterpriseDigestResource::collection($all_enterprises)
        ];
    }
}
