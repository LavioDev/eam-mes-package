<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Domain\DTOs;

class AvailabilityEquipmentParameterLogDTO
{
    public function __construct(
        public readonly string $equipmentId,
        public readonly float $availabilityPercent,
        public readonly int $runTime,
        public readonly int $stopTime,
        public readonly int $stopCount,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'equipment_id' => $this->equipmentId,
            'availability_percent' => $this->availabilityPercent,
            'time_run' => $this->runTime,
            'time_stop' => $this->stopTime,
            'stop_count' => $this->stopCount,
        ];
    }
}
