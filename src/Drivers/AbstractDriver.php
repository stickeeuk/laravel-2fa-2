<?php

namespace Stickee\Laravel2fa\Drivers;

use Closure;
use Illuminate\Support\Facades\Route;
use Stickee\Laravel2fa\Contracts\Driver;
use Stickee\Laravel2fa\Models\Laravel2fa;

abstract class AbstractDriver implements Driver
{
    /**
     * The driver name
     *
     * @var string $name
     */
    protected $name;

    /**
     * The user data manager
     *
     * @var \Stickee\Laravel2fa\Models\Laravel2fa $laravel2fa
     */
    private $laravel2fa;

    /**
     * Constructor
     *
     * @param string $name The driver name
     * @param \Stickee\Laravel2fa\Models\Laravel2fa $laravel2fa The Laravel2fa instance.
     */
    public function __construct(string $name, Laravel2fa $laravel2fa)
    {
        $this->name = $name;
        $this->laravel2fa = $laravel2fa;
    }

    /**
     * Initialise user data before they register
     */
    public function initialiseUserData(): void
    {
        if (empty($this->getData())) {
            $this->reinitialiseUserData();
        }
    }

    /**
     * Get the Laravel2fa's data for this driver
     *
     * @return array
     */
    protected function getData(): array
    {
        return $this->laravel2fa->getDriver($this->name);
    }

    /**
     * Set the Laravel2fa's data for this driver
     *
     * @param array $data The data
     */
    protected function setData(array $data): void
    {
        $this->laravel2fa->setDriver($this->name, $data);
    }

    protected static function registerRoutes(string $name, Closure $function)
    {
        Route::middleware(config('laravel-2fa.routes_middleware'))
            ->prefix(config('laravel-2fa.routes_prefix') . '/' . $name)
            ->as('laravel-2fa.' . $name . '.')
            ->group($function);
    }
}
