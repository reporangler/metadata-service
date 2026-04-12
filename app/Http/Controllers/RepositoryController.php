<?php

namespace App\Http\Controllers;

use App\Model\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller as BaseController;

class RepositoryController extends BaseController
{
    public function getList(Request $request)
    {
        $list = Repository::get();

        return new JsonResponse([
            'count' => count($list),
            'data' => $list
        ]);
    }

    public function getById(int $id)
    {
        return new JsonResponse(Repository::findOrFail($id));
    }

    public function getByName(string $repository)
    {
        return new JsonResponse(Repository::where('name', $repository)->firstOrFail());
    }

    public function create(Request $request)
    {
        Gate::authorize('package-group-create');

        $schema = [
            'name' => 'required|regex:/^'.Repository::PATTERN.'$/i',
        ];

        $data = $request->validate($schema);

        $repo = Repository::create(['name' => $data['name']]);

        return new JsonResponse($repo);
    }

    public function update(Request $request, int $id)
    {
        Gate::authorize('package-group-update');

        $schema = [
            'name' => 'required|regex:/^'.Repository::PATTERN.'$/i',
        ];

        $data = $request->validate($schema);

        $repo = Repository::findOrFail($id);

        $repo->name = $data['name'];
        $repo->save();

        return new JsonResponse($repo);
    }

    public function deleteById(Request $request, int $id)
    {
        Gate::authorize('package-group-remove');

        $repo = Repository::findOrFail($id);

        $deleted[] = $repo->toArray();
        $repo->delete();

        return new JsonResponse([
            'count' => count($deleted),
            'deleted' => $deleted
        ]);
    }
}
