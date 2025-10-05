<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

trait TracksEvents
{
    protected array $trackInclude = [];

    protected array $trackExclude = [];

    protected function shouldTrackEvent(string $event): bool
    {
        if (in_array($event, $this->trackExclude, true)) {
            return false;
        }

        if (in_array($event, $this->trackInclude, true)) {
            return true;
        }

        return false;
    }
}
