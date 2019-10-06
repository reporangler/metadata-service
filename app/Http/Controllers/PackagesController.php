<?php

namespace App\Http\Controllers;

use App\Model\Package;
use App\Model\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class PackagesController extends BaseController
{
    public function packages(Request $request, string $repository): JsonResponse
    {
        $user = Auth::guard('token')->user();

        $packageGroups = array_keys($user->package_groups);

        $packages = Package::whereRepository($repository)->get();

        return new JsonResponse($packages);
    }

    public function create(Request $request, string $repository): JsonResponse
    {
        $user = Auth::guard('token')->user();

        $schema = [
            'name' => 'required|string',
            'version' => 'required|string',
            'package_group' => 'required|string',
            'definition' => 'required|array',
        ];

        $data = $this->validate($request, $schema);

        $attributes = [
            'name' => $data['name'],
            'version' => $data['version'],
            'package_group' => $data['package_group'],
        ];

        $package = Package::whereRepository($repository)->where($attributes)->first();

        if($package){
            $package->definition = $data['definition'];
            $package->save();
        }else{
            $repo = Repository::where('name', $repository)->firstOrFail();
            $attributes['definition'] = $data['definition'];
            $package = new Package($attributes);
            $package->repository()->associate($repo);
            $package->save();
        }

        return new JsonResponse($package);
    }
}
