<?php

namespace App\Http\Controllers;

use App\Model\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class PackagesController extends BaseController
{
    public function packages(Request $request, string $repository_type): JsonResponse
    {
        $user = Auth::guard('token')->user();

        $packageGroups = array_keys($user->package_groups);

        $packages = Package::where('repository_type', $repository_type)->whereIn('package_group', $packageGroups)->get();

        return new JsonResponse($packages);
    }

    public function create(Request $request, string $repository_type): JsonResponse
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
            'repository_type' => $repository_type,
            'package_group' => $data['package_group'],
        ];

        $package = Package::where($attributes)->first();

        if($package){
            $package->definition = $data['definition'];
        }else{
            $attributes['definition'] = $data['definition'];
            $package = new Package($attributes);
        }

        $package->save();

        return new JsonResponse($package);
    }
}
