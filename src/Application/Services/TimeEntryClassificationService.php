<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

class TimeEntryClassificationService {
    public function isSubstitute(UserId $userId, ?GroupId $groupId, string $activityRole) : int {
        return 0;
    }
}
