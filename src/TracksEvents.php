<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;

trait TracksEvents
{
    /**
     * @var array<int, string>
     */
    protected array $trackInclude = [];

    /**
     * @var array<int, string>
     */
    protected array $trackExclude = [];

    /**
     * @var class-string<Model>|null
     */
    protected $userModel;

    protected function shouldTrackEvent(string $event): bool
    {
        if (in_array($event, $this->trackExclude, true)) {
            return false;
        }

        return in_array($event, $this->trackInclude, true);
    }

    /**
     * @return class-string<Model>
     */
    protected function getUserModelClass(): string
    {
        return $this->userModel ?? config('auth.providers.users.model', 'App\\Models\\User');
    }
}
