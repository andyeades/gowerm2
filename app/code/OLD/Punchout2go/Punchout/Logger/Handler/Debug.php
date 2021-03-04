<?php

namespace Punchout2go\Punchout\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class Debug extends Base
{
    /** @var */
    protected static $timezone;

    /** @var string */
    protected $name = 'Punchout2go_Punchout';

    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/punchout2go_punchout_debug.log';


    public function simple_log($message, array $context = array())
    {
        // check if any handler will handle this message so we can return early and save cycles
        $handlerKey = null;
        $level = $this->loggerType;

        if (!self::$timezone) {
            self::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        $record = array(
            'message'    => (string)$message,
            'context'    => $context,
            'level'      => $level,
            'level_name' => 'DEBUG',
            'channel'    => $this->name,
            'datetime'   => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)),
                static::$timezone)->setTimezone(static::$timezone),
            'extra'      => array(),
        );

        $this->handle($record);
    }
}
