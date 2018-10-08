<?php

namespace Helldar\NotifyExceptions\Models;

use Helldar\NotifyExceptions\Traits\FlattenException;
use Illuminate\Database\Eloquent\Model;

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

    protected function getExceptionAttribute($value)
    {
        return unserialize($value);
    }
}
