<?php

declare(strict_types=1);

use App\Domain\ClienteRepository;
use App\Infrastructure\Persistence\DataClienteRepository;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        ClienteRepository::class => autowire(DataClienteRepository::class),
    ]);
};
