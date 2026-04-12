<?php

namespace App\Http\Controllers;

use App\Model\Package;
use App\Model\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PackageController extends BaseController
{
    public function packages(Request $request, string $repository): JsonResponse
    {
        $user = $request->user();

        $query = Package::whereRepository($repository);

        // Filter by user's accessible package groups if not admin
        if ($user && !$user->is_admin_user) {
            $packageGroups = array_keys($user->package_groups ?? []);
            if (!empty($packageGroups)) {
                $query->whereIn('package_group', $packageGroups);
            } else {
                $query->where('package_group', 'public');
            }
        }

        $packages = $query->get();

        return new JsonResponse([
            'count' => count($packages),
            'data' => $packages,
        ]);
    }

    public function packagesByName(Request $request, string $repository, string $name): JsonResponse
    {
        $name = urldecode($name);

        $packages = Package::whereRepository($repository)
            ->where('name', $name)
            ->get();

        return new JsonResponse([
            'count' => count($packages),
            'data' => $packages,
        ]);
    }

    public function create(Request $request, string $repository): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'version' => 'required|string',
            'package_group' => 'required|string',
            'definition' => 'required|array',
            'storage_key' => 'nullable|string',
            'package_type' => 'nullable|string',
        ]);

        $attributes = [
            'name' => $data['name'],
            'version' => $data['version'],
            'package_group' => $data['package_group'],
        ];

        $package = Package::whereRepository($repository)->where($attributes)->first();

        if ($package) {
            $package->definition = $data['definition'];
            if (isset($data['storage_key'])) $package->storage_key = $data['storage_key'];
            if (isset($data['package_type'])) $package->package_type = $data['package_type'];
            $package->save();
        } else {
            $repo = Repository::where('name', $repository)->firstOrFail();
            $attributes['definition'] = $data['definition'];
            if (isset($data['storage_key'])) $attributes['storage_key'] = $data['storage_key'];
            if (isset($data['package_type'])) $attributes['package_type'] = $data['package_type'];
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
