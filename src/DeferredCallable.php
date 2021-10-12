<?php

namespace Zheltikov\Defer;

use Closure;

/**
 * Class DeferredCallable
 * @package Zheltikov\Defer
 */
class DeferredCallable
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * DeferredCallable constructor.
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->setCallable($callable);
    }

    /**
     * @param mixed ...$args
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return $this->call(...$args);
    }

    /**
     * TODO: optimize this method
     *
     * @param mixed ...$args
     * @return mixed
     */
    public function call(...$args)
    {
        $deferred = [];

        $defer = function (callable $callable) use (&$deferred) {
            array_unshift($deferred, $callable);
        };

        $result = call_user_func(
               $this->getCallable(),
               $defer,
            ...$args
        );

        foreach ($deferred as $callable) {
            call_user_func($callable);
        }

        return $result;
    }

    /**
     * @return \Closure
     */
    public function getClosure(): Closure
    {
        /**
         * @param mixed ...$args
         * @return mixed
         */
        return function (...$args) {
            return $this->call(...$args);
        };
    }

    // -------------------------------------------------------------------------

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function setCallable(callable $callable): self
    {
        $this->callable = $callable;
        return $this;
    }
}
