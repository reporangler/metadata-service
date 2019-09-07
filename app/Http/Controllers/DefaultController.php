<?php

namespace App\Http\Controllers;

use App\Model\Packages;
use App\Services\DatabaseAuthenticator;
use App\Services\LDAPAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
        return new JsonResponse(["statusCode" => 200, "service" => config('app.metadata_base_url')], 200);
    }

    public function packages()
    {
        $user = Auth::user();

        $packageGroups = Arr::pluck($user->package_groups, 'name');

        $packages = Packages::where('repository_type', $user->repository_type)->whereIn('package_group', $packageGroups)->get();

        return new JsonResponse($packages, 200);
    }
}
