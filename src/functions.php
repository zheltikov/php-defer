<?php

namespace Zheltikov\Defer;

/**
 * @param callable $fn The function to wrap into deferring.
 */
function wrap(callable $fn): DeferredCallable
{
    return new DeferredCallable($fn);
}
