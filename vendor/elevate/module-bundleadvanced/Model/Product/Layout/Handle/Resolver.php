<?php

namespace Elevate\BundleAdvanced\Model\Product\Layout\Handle;

/**
 * Class Resolver
 * @package Elevate\BundleAdvanced\Model\Product\Layout\Handle
 */
class Resolver
{
    /**
     * @var array
     */
    private $handlers;

    /**
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * Resolve handler by action
     *
     * @param string $action
     * @return string|null
     */
    public function resolve($action)
    {
        $handler = null;
        if (isset($this->handlers[$action])) {
            $handler = $this->handlers[$action];
        }
        return $handler;
    }
}
