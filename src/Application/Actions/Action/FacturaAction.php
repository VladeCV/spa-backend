<?php

namespace App\Application\Actions\Action;

use App\Application\Actions\Action;
use App\Domain\FacturaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class FacturaAction extends Action
{
    protected $repository;

    public function __construct(LoggerInterface $logger, FacturaRepository $repository)
    {
        parent::__construct($logger);
        $this->repository = $repository;
    }

    public function action(): Response
    {
        $data = [];
        return $this->respondWithData($data);
    }

    public function getDataLista(Request $request, Response $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $res = $this->repository->getDataLista();
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }

    public function getDataId(Request $request, Response $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $body['id_factura'] = $args['id'];
        $res = $this->repository->getDataId($body);
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }

    public function setDataSave(Request $request, Response $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $body = $request->getParsedBody();
        $res = $this->repository->setDataSave($body);
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }

    public function getFacturaByClienteIdLista(Request $request, Response $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $body['id_cliente'] = $args['id_cliente'];
        $res = $this->repository->getFacturaByClienteIdLista($body);
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }
    public function cambiarEstadoFactura(Request $request, Response $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $body = $request->getParsedBody();
        $body['id_factura'] = $args['id'];
        $res = $this->repository->cambiarEstadoFactura($body);
        return $this->respondWithData($res['data'], $res['message'], $res['statusCode'], $res['success']);
    }
}
