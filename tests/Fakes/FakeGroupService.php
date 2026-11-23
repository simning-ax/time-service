<?php

declare(strict_types=1);

namespace Tests\Fakes;

use App\Application\Services\GroupService;
use App\Domain\ValueObjects\UserId;

class FakeGroupService implements GroupService {
    /**
     * @inheritDoc
     */
    public function getGroupsForUser(UserId $userId) : array {
        return [];
    }
}
