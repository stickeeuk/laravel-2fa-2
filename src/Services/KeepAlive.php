<?php

namespace Stickee\Laravel2fa\Services;

use Carbon\Carbon;
use Stickee\Laravel2fa\Contracts\StateStore;

/**
 */
class KeepAlive
{
    /**
     * The state store
     *
     * @var \Stickee\Laravel2fa\Contracts\StateStore $stateStore
     */
    private $stateStore;

    /**
     * Constructor
     *
     * @param \Stickee\Laravel2fa\Contracts\StateStore $stateStore The state store
     */
    public function __construct(StateStore $stateStore)
    {
        $this->stateStore = $stateStore;
    }

    /**
     * Check if the authorisation has expired
     *
     * @return bool
     */
    public function expired(): bool
    {
        $lifetime = (int)config('laravel-2fa.lifetime', 0);

        $expired = $lifetime && ($this->getMinutesSinceLastActivity() > $lifetime);

        if (!$expired && config('laravel-2fa.keep_alive')) {
            $this->updateLastActivityTime();
        }

        return $expired;
    }

    /**
     * Update the last activity time
     */
    public function updateLastActivityTime()
    {
        $this->stateStore->put('last_activity', Carbon::now());
    }

    /**
     * Get minutes since last activity
     *
     * @return int
     */
    private function getMinutesSinceLastActivity()
    {
        $authTime = $this->stateStore->get('last_activity');

        if ($authTime === null) {
            return PHP_INT_MAX;
        }

        return Carbon::now()->diffInMinutes($authTime);
    }
}
