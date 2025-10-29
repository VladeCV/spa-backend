<?php

use App\Application\Actions\Action\FacturaAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/api/v1/factura', function (Group $group) {
        $group->get('/lista', FacturaAction::class . ':getDataLista');
        $group->get('/{id}', FacturaAction::class . ':getDataId');
        $group->get('/cliente/{id_cliente}/lista', FacturaAction::class . ':getFacturaByClienteIdLista');
        $group->post('', FacturaAction::class . ':setDataSave');
        $group->patch('/estado/{id}', FacturaAction::class . ':cambiarEstadoFactura');
    });
};
