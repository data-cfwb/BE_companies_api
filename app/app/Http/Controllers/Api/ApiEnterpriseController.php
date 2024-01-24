<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Enterprise;
use App\Models\Denomination;

use App\Http\Resources\EnterpriseResource;
use App\Http\Resources\EnterpriseDigestResource;

use Helper;

class ApiEnterpriseController extends Controller
{
    /**
    * @OA\Get(
    * path="/enterprises/{EnterpriseNumber}",
    * summary="Get the homepage of the API",
    * description="Links to API resources",
    * operationId="enterprise.show",
    * tags={"Main"},
    * @OA\Response(
    *    response=200,
    *    description="Success",
    * )
    * )
    * )
    */
    public function show($EnterpriseNumberRequest)
    {
        $EnterpriseNumber = Helper::fixEnterpriseNumber($EnterpriseNumberRequest);
        
        $enterprise = Enterprise::where('EnterpriseNumber', $EnterpriseNumber)->with(
            ['addresses', 'denominations', 'contacts', 'establishments', 'activities', 'branches']
        )->firstOrFail();

        $enterprise->EnterpriseNumberRequest = $EnterpriseNumberRequest;

        return new EnterpriseResource($enterprise);
    }

    public function showDigest($EnterpriseNumberRequest)
    {
        $EnterpriseNumber = Helper::fixEnterpriseNumber($EnterpriseNumberRequest);

        $enterprise = Enterprise::where('EnterpriseNumber', $EnterpriseNumber)->with(
            ['addresses', 'denominations', 'contacts', 'establishments', 'activities', 'branches']
        )->firstOrFail();

        $enterprise->EnterpriseNumberRequest = $EnterpriseNumberRequest;

        return new EnterpriseDigestResource($enterprise);
    }

    public function random()
    {
        # find a random enterprise
        $enterprise = Enterprise::whereHas('subsidies')->inRandomOrder()->first();

        return new EnterpriseResource($enterprise);
    }
}
