<?php

declare(strict_types=1);

namespace App\Domain\DataTransportObjects;

use App\Domain\ValueObjects\GroupId;

class GroupMembershipDTO {
    public function __construct(
        public GroupId $groupId,
        public string $role,
    ) {
    }
}
