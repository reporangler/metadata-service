<?php

namespace App\Http\Controllers;

use App\Model\Package;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DefaultController extends BaseController
{
    public function cors($args): JsonResponse
    {
        return $this->healthz();
    }

    public function healthz(): JsonResponse
    {
        return new JsonResponse(["statusCode" => 200, "service" => config('app.metadata_base_url')], 200);
    }

    public function packages(): JsonResponse
    {
        $user = Auth::user();

        $packageGroups = array_keys($user->package_groups);

        $packages = Package::where('repository_type', $user->repository_type)->whereIn('package_group', $packageGroups)->get();

        return new JsonResponse($packages, 200);
    }

    public function create(Request $request): JsonResponse
    {
        $repositoryType = $request->headers->get('reporangler-repository-type');

        $schema = [
            'package_group' => 'required|string',
            'name' => 'required|string',
            'version' => 'required|string',
            'definition' => 'required|array',
        ];

        $data = $this->validate($request, $schema);

        $package = new Package();
        $package->name = $data['name'];
        $package->version = $data['version'];
        $package->repository_type = $repositoryType;
        $package->package_group = $data['package_group'];
        $package->definition = $data['definition'];
        $package->save();

        return new JsonResponse($package, 200);
    }
}
