<?php
declare(strict_types=1);
/**
 * Handler
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Logger\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class Handler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/firebear-icp.log';
}
