<?php

declare(strict_types=1);

use App\Domain\ClienteRepository;
use App\Domain\FacturaRepository;
use App\Infrastructure\Persistence\DataClienteRepository;
use App\Infrastructure\Persistence\DataFacturaRepository;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ClienteRepository::class => autowire(DataClienteRepository::class),
        FacturaRepository::class => autowire(DataFacturaRepository::class),
    ]);
};
