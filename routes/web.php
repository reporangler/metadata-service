<?php
use RepoRangler\Entity\Repository;
use RepoRangler\Entity\PackageGroup;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Healthcheck for any monitoring software
$router->get('/healthz', 'DefaultController@healthz');

$router->group(['middleware' => ['cors']], function() use ($router) {
    // Set the CORS options that we will allow web requests from (This doesn't affect composer/console clients)
    $router->options('{path:.*}', 'DefaultController@cors');

    // Pass all requests through the auth layer
    $router->group(['middleware' => ['auth:token']], function() use ($router) {
        $router->group(['prefix' => 'packages'], function() use ($router) {
            $pattern = Repository::PATTERN;

            $router->get("/{repository:$pattern}", 'PackageController@packages');
            $router->post("/{repository:$pattern}", 'PackageController@create');
        });

        $router->group(['prefix' => 'package-group'], function() use ($router) {
            $pattern = PackageGroup::PATTERN;

            $router->get("/{name:$pattern}", 'PackageGroupController@findByName');
            $router->get('/{id:[0-9]+}',         'PackageGroupController@findById');
            $router->get('/',                       'PackageGroupController@getList');
            $router->post('/',                      'PackageGroupController@create');
            $router->put('/',                       'PackageGroupController@update');
            $router->delete('/{id:[0-9]+}',         'PackageGroupController@deleteById');
        });

        $router->group(['prefix' => 'repository'], function() use ($router) {
            $pattern = Repository::PATTERN;

            $router->get('/',                       'RepositoryController@getList');
            $router->get("/{repository:$pattern}",  'RepositoryController@getByName');
            $router->post('/',                      'RepositoryController@create');
            $router->put('/{id:[0-9]+}',            'RepositoryController@update');
            $router->delete('/{id:[0-9]+}',         'RepositoryController@deleteById');
        });
    });
});
