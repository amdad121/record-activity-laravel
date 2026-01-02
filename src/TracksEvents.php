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

        return in_array($event, $this->trackInclude, true);
    }

    protected function getUserModelClass(): string
    {
        return property_exists($this, 'userModel')
            ? $this->userModel
            : config('auth.providers.users.model', 'App\\Models\\User');
    }
}
