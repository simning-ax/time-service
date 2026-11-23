<?php

declare(strict_types=1);

namespace App\Application\Handlers\TimeEntry;

use App\Application\Commands\TimeEntry\TimeEntryCommand;
use App\Application\Services\TimeEntryClassificationService;
use App\Domain\DataTransportObjects\TimeEntryDTO;
use App\Domain\Entities\TimeEntry;
use App\Domain\Exception\FutureDateNotAllowedException;
use App\Domain\Repositories\TimeEntryRepository;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\TimeEntryId;

class AddTimeEntryHandler extends TimeEntryHandler {
    public function __construct(
        TimeEntryRepository $repository,
        private TimeEntryClassificationService $classificationService,
    ) {
        parent::__construct($repository);
    }

    public function handle(TimeEntryCommand $command) : TimeEntryDTO {
        // Affärsregler
        if ($command->date->isFuture()) {
            throw new FutureDateNotAllowedException(
                'Tidsposter kan inte registreras för framtida datum',
            );
        }
        $isSubstitute = $this->classificationService->isSubstitute(
            $command->userId,
            $command->groupId,
            $command->activityRole,
        );

        $timeEntry = new TimeEntry(
            new TimeEntryId(),
            $command->userId,
            $command->date,
            $command->duration,
            $command->activityRole,
            $command->groupId,
            $command->comment,
            $isSubstitute,
            null,
            null,
            null,
            null,
            new DateTimeValue('now'),
            null,
        );

        $this->repository->add($timeEntry);

        $dto = TimeEntryDTO::fromEntity($timeEntry);

        return $dto;
    }
}
