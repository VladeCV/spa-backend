<?php

namespace App\Domain;

interface FacturaRepository
{

    public function getDataLista();

    public function getDataId($body): array;

    public function setDataSave($body): array;

    public function getFacturaByClienteIdLista($body): array;

    public function cambiarEstadoFactura($body): array;
}