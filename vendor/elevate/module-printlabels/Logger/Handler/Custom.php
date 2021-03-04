<?php

namespace Elevate\PrintLabels\Logger\Handler;

use Monolog\Logger;

class Custom extends \Magento\Framework\Logger\Handler\Base {

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
    protected $fileName = '/var/log/elevate_printlabels.log';
}
