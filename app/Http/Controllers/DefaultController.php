<?php

namespace App\Http\Controllers;

use App\Services\DatabaseAuthenticator;
use App\Services\LDAPAuthenticator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DefaultController extends BaseController
{
    public function cors($args)
    {
        return $this->healthz();
    }

    public function healthz()
    {
        return new JsonResponse(["statusCode" => 200, "service" => config('app.auth_base_url')], 200);
    }

    public function packages()
    {
        $packages = json_decode(file_get_contents("/www/all$12341234.json"),true);

        return new JsonResponse($packages, 200);
    }
}