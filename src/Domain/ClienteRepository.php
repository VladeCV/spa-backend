<?php

namespace App\Domain;

interface ClienteRepository
{
    public function getDataLista(): array;

    public function getDataId($body): array;

    public function setDataSave($body): array;

    public function setDataPut($body): array;

    public function setDelete($body): array;
}
