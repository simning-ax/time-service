<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

class TimeEntryClassificationService {
    public function __construct(private GroupService $groupService) {
    }

    public function isSubstitute(UserId $userId, ?GroupId $groupId, string $activityRole) : int {
        if ($groupId === null) {
            return 0;
        }

        $memberships = $this->groupService->getGroupsForUser($userId);

        foreach ($memberships as $membership) {
            if (
                $membership->groupId->equals($groupId)
                && $membership->role === $activityRole
            ) {
                return 0;
            }
        }

        return 1;
    }
}
