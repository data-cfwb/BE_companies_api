<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

use App\Models\Enterprise;
use App\Models\Activity;
use App\Models\Establishment;
use App\Models\Code;
use App\Models\Subsidy;

class ApiSubsidyController extends BaseController
{
    public function get()
    {
        $distinct_competences = Subsidy::distinct('Compétence')->pluck('Compétence')->toArray();

        return [
            'distinct_competences' => $distinct_competences
        ];

    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show($subsidy_category)
    {
        $subsidies = Subsidy::where('Compétence', $subsidy_category)->get();

        $enterprises_number = $subsidies->pluck('EnterpriseNumber')->toArray();
       
        # get the enterprises
        $enterprises = Enterprise::whereIn('EnterpriseNumber', $enterprises_number)->with(
            ['addresses', 'denominations', 'contacts', 'establishments', 'activities', 'branches']
        )->get();

        return [
            'subsidies' => $subsidies,
            'enterprises' => $enterprises,
        ];

    }


}
