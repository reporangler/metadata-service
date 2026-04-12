<?php

namespace App\Http\Controllers;

use App\Model\Package;
use App\Model\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PackageController extends BaseController
{
    public function packages(Request $request, string $repository): JsonResponse
    {
        $user = Auth::guard('token')->user();

        $packageGroups = array_keys($user->package_groups);

        $query = Package::whereRepository($repository);

        // Filter by user's accessible package groups if not admin
        if (!$user->is_admin_user && !empty($packageGroups)) {
            $query->whereIn('package_group', $packageGroups);
        } elseif (!$user->is_admin_user && empty($packageGroups)) {
            // Non-admin with no groups can only see public packages
            $query->where('package_group', 'public');
        }

        $packages = $query->get();

        return new JsonResponse([
            'count' => count($packages),
            'data' => $packages,
        ]);
    }

    public function create(Request $request, string $repository): JsonResponse
    {
        $schema = [
            'name' => 'required|string',
            'version' => 'required|string',
            'package_group' => 'required|string',
            'definition' => 'required|array',
        ];

        $data = $request->validate($schema);

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

    public function deleteById(Request $request, string $repository, int $id): JsonResponse
    {
        $package = Package::whereRepository($repository)->where('id', $id)->firstOrFail();

        $deleted = $package->toArray();
        $package->delete();

        return new JsonResponse(['deleted' => [$deleted]]);
    }
}
