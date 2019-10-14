<?php

namespace App\Http\Controllers;

use App\Model\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

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

    public function getByName(string $repository)
    {
        return new JsonResponse(Repository::where('name', $repository)->firstOrFail());
    }

    public function create(Request $request)
    {
        $schema = [
            'name' => 'required|regex:/^'.Repository::PATTERN.'$/i',
        ];

        $data = $this->validate($request, $schema);

        $repo = Repository::create(['name' => $data['name']]);

        return new JsonResponse($repo);
    }

    public function update(Request $request, int $id)
    {
        $schema = [
            'name' => 'required|regex:/^'.Repository::PATTERN.'$/i',
        ];

        $data = $this->validate($request, $schema);

        $repo = Repository::findOrFail($id);

        $repo->name = $data['name'];
        $repo->save();

        return new JsonResponse($repo);
    }

    public function deleteById(Request $request, int $id)
    {
        $repo = Repository::find($id);

        $deleted = [];

        if ($repo){
            $deleted[] = $repo->toArray();
            $repo->delete();
        }

        return new JsonResponse([
            'count' => count($deleted),
            'deleted' => $deleted
        ], count($deleted) ? 200 : 404);
    }
}
