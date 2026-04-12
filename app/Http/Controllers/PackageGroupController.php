<?php

namespace App\Http\Controllers;

use App\Model\PackageGroup;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PackageGroupController extends BaseController
{
    public function getByName(string $name): JsonResponse
    {
        return new JsonResponse(PackageGroup::where('name', $name)->firstOrFail(),200);
    }

    public function getById(int $id): JsonResponse
    {
        return new JsonResponse(PackageGroup::findOrFail($id),200);
    }

    public function getList(): JsonResponse
    {
        $list = PackageGroup::all();

        return new JsonResponse([
            'count' => count($list),
            'data' => $list
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        Gate::authorize('package-group-create');

        $authSchema = [
            'name' => 'required|string',
        ];

        $data = $request->validate($authSchema);

        $result = PackageGroup::where('name', $data['name'])->first();

        if(!empty($result)){
            throw new UnprocessableEntityHttpException("Package Group '{$data['name']}' already exists");
        }

        $packageGroup = new PackageGroup();
        $packageGroup->name = $data['name'];
        $packageGroup->save();

        return new JsonResponse($packageGroup, 200);
    }

    public function update(Request $request): JsonResponse
    {
        Gate::authorize('package-group-update');

        $authSchema = [
            'id' => 'required|integer|min:1',
            'name' => 'required|string',
        ];

        $data = $request->validate($authSchema);

        $packageGroup = PackageGroup::findOrFail($data['id']);
        $packageGroup->name = $data['name'];
        $packageGroup->save();

        return new JsonResponse($packageGroup, 200);
    }

    public function deleteById(Request $request, int $id): JsonResponse
    {
        Gate::authorize('package-group-remove');

        $packageGroup = PackageGroup::findOrFail($id);

        $deleted[] = $packageGroup->toArray();

        $packageGroup->delete();

        return new JsonResponse(['deleted' => $deleted], 200);
    }
}
