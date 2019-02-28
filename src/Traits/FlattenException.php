<?php

namespace Helldar\Notifex\Traits;

trait FlattenException
{
    /**
     * @param \Exception $exception
     *
     * @see https://gist.github.com/Thinkscape/805ba8b91cdce6bcaf7c
     *
     * @throws \ReflectionException
     */
    public function flattenExceptionBacktrace(\Exception $exception)
    {
        $traceProperty = (new \ReflectionClass('Exception'))->getProperty('trace');
        $traceProperty->setAccessible(true);

        $flatten = function (&$value) {
            if ($value instanceof \Closure) {
                $closureReflection = new \ReflectionFunction($value);
                $value             = sprintf('(Closure at %s:%s)', $closureReflection->getFileName(), $closureReflection->getStartLine());
            } elseif (is_object($value)) {
                $value = sprintf('object(%s)', get_class($value));
            } elseif (is_resource($value)) {
                $value = sprintf('resource(%s)', get_resource_type($value));
            }
        };

        do {
            $trace = $traceProperty->getValue($exception);
            foreach ($trace as &$call) {
                array_walk_recursive($call['args'], $flatten);
            }
            $traceProperty->setValue($exception, $trace);
        } while ($exception = $exception->getPrevious());

        $traceProperty->setAccessible(false);
    }
}
