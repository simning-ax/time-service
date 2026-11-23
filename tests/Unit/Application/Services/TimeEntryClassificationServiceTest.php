<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\GroupService;
use App\Application\Services\TimeEntryClassificationService;
use App\Domain\DataTransportObjects\GroupMembershipDTO;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class TimeEntryClassificationServiceTest extends TestCase {
    public function testReturnsZeroWhenGroupAndRoleMatch() : void {
        $userId = new UserId('550e8400-e29b-41d4-a716-446655440000', );
        $groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $groupService = $this->createMock(GroupService::class);

        $groupService
            ->expects($this->once())
            ->method('getGroupsForUser')
            ->with($userId)
            ->willReturn([new GroupMembershipDTO($groupId, 'Ledare', ), ]);

        $service = new TimeEntryClassificationService($groupService, );

        self::assertSame(0, $service->isSubstitute($userId, $groupId, 'Ledare'));
    }

    public function testReturnsOneWhenRoleDiffers() : void {
        $userId = new UserId('550e8400-e29b-41d4-a716-446655440000', );
        $groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $groupService = $this->createMock(GroupService::class);

        $groupService
            ->expects($this->once())
            ->method('getGroupsForUser')
            ->with($userId)
            ->willReturn([new GroupMembershipDTO($groupId, 'Assistent', ), ]);

        $service = new TimeEntryClassificationService($groupService, );

        self::assertSame(1, $service->isSubstitute($userId, $groupId, 'Ledare'));
    }

    public function testReturnsOneWhenGroupDiffers() : void {
        $userId = new UserId('550e8400-e29b-41d4-a716-446655440000', );

        $groupService = $this->createMock(GroupService::class);

        $groupService
            ->expects($this->once())
            ->method('getGroupsForUser')
            ->with($userId)
            ->willReturn([
                new GroupMembershipDTO(
                    new GroupId('550e8400-e29b-41d4-a716-446655440001', ),
                    'Ledare',
                ),
            ]);

        $service = new TimeEntryClassificationService($groupService, );

        self::assertSame(
            1,
            $service->isSubstitute($userId, new GroupId('550e8400-e29b-41d4-a716-446655440000', ), 'Ledare'),
        );
    }

    public function testReturnsZeroWhenGroupIdIsNull() : void {
        $userId = new UserId('550e8400-e29b-41d4-a716-446655440000', );

        $groupService = $this->createMock(
            GroupService::class,
        );

        $groupService
            ->expects($this->never())
            ->method('getGroupsForUser');

        $service = new TimeEntryClassificationService($groupService, );

        self::assertSame(0, $service->isSubstitute($userId, null, 'Ledare'));
    }
}
