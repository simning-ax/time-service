<?php

declare(strict_types=1);

namespace Tests\Integration\Http\TimeEntry;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;

final class SearchTimeEntriesEndpointTest extends BaseApiTestCases {
    public function testReturns200WhenSearchingTimeEntries() : void {
        $this->loadSchema('time_entries');

        $this->seed('time_entries', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
                'user_id' => '550e8400-e29b-41d4-a716-446655440000',
                'date' => '2026-11-05',
                'duration' => '03:05:00',
                'activity_role' => 'Ledare',
                'group_id' => null,
                'comment' => 'Testkommentar',
                'approved_by' => null,
                'approved_at' => null,
                'exported_by' => null,
                'exported_at' => null,
                'created_at' => '2026-11-05 12:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = $this->authenticate(
            (new ServerRequestFactory())
                ->createServerRequest('POST', '/time-entry/search'),
        )->withHeader('Content-Type', 'application/json');
        $request->getBody()->write('{}');

        $response = $this->app->handle($request);

        self::assertSame(200, $response->getStatusCode());

        $this->validateRequest($request);
        $this->validateResponse('/time-entry/search', 'post', $response);
    }

    public function testReturns200WhenSearchingOnlyDeviationEntries() : void {
        $this->loadSchema('users');
        $this->loadSchema('time_entries');

        $this->seed('users', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'Test',
                'last_name' => 'User',
                'active' => 1,
                'created_at' => '2026-01-01 10:00:00',
            ],
        ]);

        $this->seed('time_entries', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
                'user_id' => '550e8400-e29b-41d4-a716-446655440000',
                'date' => '2026-11-05',
                'duration' => '03:05:00',
                'activity_role' => 'Ledare',
                'group_id' => '660e8400-e29b-41d4-a716-446655440000',
                'comment' => 'Normal post',
                'is_substitute' => 0,
                'approved_by' => null,
                'approved_at' => null,
                'exported_by' => null,
                'exported_at' => null,
                'created_at' => '2026-11-05 12:00:00',
                'updated_at' => null,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440002',
                'user_id' => '550e8400-e29b-41d4-a716-446655440000',
                'date' => '2026-11-06',
                'duration' => '02:00:00',
                'activity_role' => 'Ledare',
                'group_id' => '660e8400-e29b-41d4-a716-446655440001',
                'comment' => 'Vikariepost',
                'is_substitute' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'exported_by' => null,
                'exported_at' => null,
                'created_at' => '2026-11-06 12:00:00',
                'updated_at' => null,
            ],
        ]);

        $requestBody = ['deviationsOnly' => true, ];

        $request = $this->authenticate(
            (new ServerRequestFactory())
                ->createServerRequest('POST', '/time-entry/search')
                ->withHeader('Content-Type', 'application/json'),
        );

        $request->getBody()->write(json_encode($requestBody, JSON_THROW_ON_ERROR));

        $request = $request->withParsedBody($requestBody);

        $response = $this->app->handle($request);

        self::assertSame(200, $response->getStatusCode());

        $body = json_decode(
            (string)$response->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertCount(1, $body['data']['timeEntries']);

        self::assertSame('550e8400-e29b-41d4-a716-446655440002', $body['data']['timeEntries'][0]['id']);

        $this->validateRequest($request);
        $this->validateResponse('/time-entry/search', 'post', $response);
    }

    public function testReturns200WhenSearchingWithDeviationsOnlyFalse() : void {
        $this->loadSchema('users');
        $this->loadSchema('time_entries');

        $this->seed('users', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'Test',
                'last_name' => 'User',
                'active' => 1,
                'created_at' => '2026-01-01 10:00:00',
            ],
        ]);

        $this->seed('time_entries', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
                'user_id' => '550e8400-e29b-41d4-a716-446655440000',
                'date' => '2026-11-05',
                'duration' => '03:05:00',
                'activity_role' => 'Ledare',
                'group_id' => '660e8400-e29b-41d4-a716-446655440000',
                'comment' => 'Normal post',
                'is_substitute' => 0,
                'approved_by' => null,
                'approved_at' => null,
                'exported_by' => null,
                'exported_at' => null,
                'created_at' => '2026-11-05 12:00:00',
                'updated_at' => null,
            ],
            [
                'id' => '550e8400-e29b-41d4-a716-446655440002',
                'user_id' => '550e8400-e29b-41d4-a716-446655440000',
                'date' => '2026-11-06',
                'duration' => '02:00:00',
                'activity_role' => 'Ledare',
                'group_id' => '660e8400-e29b-41d4-a716-446655440001',
                'comment' => 'Vikariepost',
                'is_substitute' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'exported_by' => null,
                'exported_at' => null,
                'created_at' => '2026-11-06 12:00:00',
                'updated_at' => null,
            ],
        ]);

        $requestBody = ['deviationsOnly' => false, ];

        $request = $this->authenticate(
            (new ServerRequestFactory())
                ->createServerRequest('POST', '/time-entry/search')
                ->withHeader('Content-Type', 'application/json'),
        );

        $request->getBody()->write(json_encode($requestBody, JSON_THROW_ON_ERROR));

        $request = $request->withParsedBody($requestBody);

        $response = $this->app->handle($request);

        self::assertSame(200, $response->getStatusCode());

        $body = json_decode(
            (string)$response->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertCount(2, $body['data']['timeEntries']);

        $this->validateRequest($request);

        $this->validateResponse('/time-entry/search', 'post', $response);
    }

    public function testReturns401WhenAuthenticationIsMissing() : void {
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/time-entry/search')
            ->withHeader('Content-Type', 'application/json');

        $response = $this->app->handle($request);

        self::assertSame(401, $response->getStatusCode());

        $this->validateResponse('/time-entry/search', 'post', $response);
    }

    public function testReturns403WhenUserLacksViewPermission() : void {
        $request = $this->authenticate(
            (new ServerRequestFactory())
                ->createServerRequest('POST', '/time-entry/search')
                ->withHeader('Content-Type', 'application/json'),
        )->withoutHeader('X-Service-Permissions');

        $response = $this->app->handle($request);

        self::assertSame(
            403,
            $response->getStatusCode(),
        );

        $this->validateResponse('/time-entry/search', 'post', $response);
    }

    public function testReturns422WhenUserIdIsInvalid() : void {
        $request = $this->authenticate(
            (new ServerRequestFactory())
                ->createServerRequest('POST', '/time-entry/search')
                ->withHeader('Content-Type', 'application/json'),
        );

        $payload = ['users' => ['invalid', ], ];

        $request->getBody()->write(json_encode($payload, JSON_THROW_ON_ERROR));

        $request = $request->withParsedBody($payload);

        $response = $this->app->handle($request);

        self::assertSame(422, $response->getStatusCode());

        $this->validateResponse('/time-entry/search', 'post', $response);
    }
}
