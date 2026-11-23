<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\DataTransportObjects\GroupMembershipDTO;
use App\Domain\ValueObjects\UserId;

interface GroupService {
    /**
     * @return array<GroupMembershipDTO>
     */
    public function getGroupsForUser(UserId $userId) : array;
}
