<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\HomePageResource;
use App\Http\Controllers\Controller;
use App\Models\Enterprise;

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
            'enterprises' => Enterprise::whereHas('subsidies')->count(),
            'naces' => '',
            'codes' => ''
        ]);
    }
}
