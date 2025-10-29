<?php

use App\Application\Actions\Action\ClienteAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/api/v1/cliente', function (Group $group) {
        $group->get('/lista', ClienteAction::class . ':getDataLista');
        $group->get('/{id}', ClienteAction::class . ':getDataId');
        $group->post('', ClienteAction::class . ':setDataSave');
        $group->put('/{id}', ClienteAction::class . ':setDataPut');
        $group->delete('/{id}', ClienteAction::class . ':setDelete');
    });
};
