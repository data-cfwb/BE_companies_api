<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\HomePageResource;
use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\Subsidy;

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
                        'name' => 'Nombre d\'organismes subventionnés',
                        'stat' => Subsidy::distinct('Postal-OrgName_Slug')->count(),
                        'unit' => 'organismes'
                    ],
                    [
                        'name' => 'Montant total des subventions',
                        'stat' => round(Subsidy::sum('AmountInEuros') / 1000000000, 3),
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
}
