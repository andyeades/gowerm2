<?php

namespace Elevate\Delivery\Logger\Handler;

use Monolog\Logger;

/**
 * Class Custom
 *
 * @category Elevate
 * @package  Elevate\Delivery\Logger\Handler
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Custom extends \Magento\Framework\Logger\Handler\Base
{

    /**
     * Logging level
     *
     * @var int
     */
    protected $_loggerType = Logger::INFO;

    /**
     * File Name
     *
     * @var string
     */
    protected $fileName = '/var/log/elevate_delivery.log';
}
