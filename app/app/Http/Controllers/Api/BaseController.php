<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\HomePageResource;
use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\Subsidy;
use App\Models\Denomination;

use App\Http\Resources\EnterpriseDigestResource;


class BaseController extends Controller
{
    /**
    * @OA\Get(
    * path="/",
    * summary="Get the homepage of the API",
    * description="Links to API resources",
    * operationId="main",
    * tags={"Main"},
    * @OA\Response(
    *    response=200,
    *    description="Success",
    * )
    * )
    * )
    */
    public function index()
    {
        return new HomePageResource('Hello world!');
    }

    /**
     * @OA\Get(
     * path="/stats",
     * summary="Get the stats of the API",
     * description="Stats of the API",
     * operationId="stats",
     * tags={"Main"},
     * @OA\Response(
     *   response=200,
     *  description="Success",
     * )
     * )
     * )
     */
    public function stats()
    {
            return response()->json([
                'data' => [
                    [
                        'name' => 'Nombre d\'organisations subventionnées',
                        'stat' => Subsidy::distinct('Postal-OrgName_Slug')->count(),
                        'unit' => 'organisations'
                    ],
                    [
                        'name' => 'Montant total des subventions répertoriées',
                        'stat' => Subsidy::sum('AmountInEuros') / 1000000000, // in billions
                        'unit' => 'milliards d\'euros'
                    ],
                    [
                        'name' => 'Nombre total de subventions',
                        'stat' => Subsidy::count(),
                        'unit' => 'subventions'
                    ]
                ]
            ]);
        
    }

    // get arguments from the url
    public function lookup(Request $request)
    {
        $enterprisesNumber = Denomination::where('Denomination', 'like', '%' . $request->input('name') . '%')->pluck('EntityNumber')->toArray();

        # filter with Zipcode
        if ($request->input('Zipcode')) {
            $enterprises = Enterprise::whereIn('EnterpriseNumber', $enterprisesNumber)->with(
                ['addresses', 'denominations']
            )->whereHas('addresses', function ($query) use ($request) {
                $query->where('Zipcode', $request->input('Zipcode'));
            })->get()->sortByDesc('subsidies_sum');
        } else {
            $enterprises = Enterprise::whereIn('EnterpriseNumber', $enterprisesNumber)->with(
                ['addresses', 'denominations']
            )->get()->sortByDesc('subsidies_sum');
        }

        $enterprises->load('contacts', 'establishments', 'activities', 'branches');
        
        // return the data with the input
        return [
            'input' => $request->all(),
            'results' => $enterprises->count(),
            // using ressource
            'enterprises' => EnterpriseDigestResource::collection($enterprises),
        ];
    }
}
