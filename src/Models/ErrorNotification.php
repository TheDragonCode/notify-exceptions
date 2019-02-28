<?php

namespace Helldar\Notifex\Models;

use Helldar\Notifex\Traits\FlattenException;
use Illuminate\Database\Eloquent\Model;

/**
 * Helldar\Notifex\Models\ErrorNotification
 *
 * @property int $id
 * @property string $parent
 * @property \Exception $exception
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder whereUpdatedAt($value)
 */
class ErrorNotification extends Model
{
    use FlattenException;

    protected $fillable = ['parent', 'exception'];

    /**
     * @param \Exception $value
     *
     * @throws \ReflectionException
     */
    protected function setExceptionAttribute($value)
    {
        $this->flattenExceptionBacktrace($value);

        $this->attributes['exception'] = serialize($value);
    }

    /**
     * @param string $value
     *
     * @return \Exception
     */
    protected function getExceptionAttribute($value)
    {
        return unserialize($value);
    }
}
