<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class DefaultController extends BaseController
{
    public function cors(): JsonResponse
    {
        return $this->healthz();
    }

    public function healthz(): JsonResponse
    {
        return new JsonResponse(["statusCode" => 200, "service" => config('app.metadata_base_url')]);
    }
}
