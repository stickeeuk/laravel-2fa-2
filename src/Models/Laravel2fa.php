<?php

namespace Stickee\Laravel2fa\Models;

use Illuminate\Database\Eloquent\Model;

class Laravel2fa extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type',
        'user_id',
        'enabled',
        'data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('laravel-2fa.table_name');
    }

    /**
     * Get the owning user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Get the data.
     *
     * @param array $value
     * @return void
     */
    public function getDataAttribute(): array
    {
        return empty($this->attributes['data'])
            ? $this->attributes['data']
            : decrypt($this->attributes['data']);
    }

    /**
     * Set the data.
     *
     * @param array $value
     * @return void
     */
    public function setDataAttribute(array $value)
    {
        $this->attributes['data'] = encrypt($value);
    }

    /**
     * Get a value in the Laravel2fa's data
     *
     * @param string $key The key
     * @param mixed $default The default value
     *
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Set a value in the Laravel2fa's data
     *
     * @param string $key The key
     * @param string $value The value
     */
    public function setValue($key, $value): void
    {
        $data = $this->data;
        $data[$key] = $value;

        $this->update(['data' => $data]);
    }

    /**
     * Get the Laravel2fa's data for a driver
     *
     * @param string $driverName The driver name
     * @return array
     */
    public function getDriver(string $driverName): array
    {
        $drivers = $this->getValue('drivers', []);

        return $drivers[$driverName] ?? [];
    }

    /**
     * Set the Laravel2fa's data for a driver
     *
     * @param string $driverName The driver name
     * @param array $data The user's driver data
     */
    public function setDriver(string $driverName, array $data): void
    {
        $drivers = $this->getValue('drivers', []);

        $drivers[$driverName] = $data;

        $this->setValue('drivers', $drivers);
    }

    /**
     * Get the first instance of a Laravel2fa model with the given user type and user id.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return null|\Stickee\Laravel2fa\Models\Laravel2fa
     */
    public static function getByModel($model)
    {
        return Laravel2fa::where('user_type', $model->getMorphClass())->where('user_id', $model->getKey())->first();
    }
}
