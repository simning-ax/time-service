<?php

declare(strict_types=1);

namespace App\Infrastructure\Group;

use App\Application\Services\GroupService;
use App\Domain\DataTransportObjects\GroupMembershipDTO;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use GuzzleHttp\Client;

class GroupServiceClient implements GroupService {
    public function __construct(private Client $client) {
    }

    /**
     * @inheritDoc
     */
    public function getGroupsForUser(UserId $userId) : array {
        $response = $this->client->get(
            '/users/' . $userId->toString() . '/groups',
        );

        $rows = json_decode(
            (string)$response->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        return array_map(
            fn (array $row) : GroupMembershipDTO => new GroupMembershipDTO(
                new GroupId($row['groupId']),
                $row['role'],
            ),
            $rows,
        );
    }
}
