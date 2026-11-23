<?php

declare(strict_types=1);

use App\Application\Services\GroupService;
use DI\ContainerBuilder;
use Tests\Fakes\FakeGroupService;

return function (ContainerBuilder $builder) {

    $builder->addDefinitions([
        GroupService::class => function () {
            return new FakeGroupService();
        },

    ]);
};
