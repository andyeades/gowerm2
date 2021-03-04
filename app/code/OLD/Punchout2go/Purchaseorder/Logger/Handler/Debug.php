<?php
namespace Punchout2go\Purchaseorder\Logger\Handler;

use Monolog\Logger;

class Debug
    extends \Magento\Framework\Logger\Handler\Base
{
    /** @var   */
    protected static $timezone;

    /** @var string  */
    protected $name = 'Punchout2go_Purchaseorder';

    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/punchout2go_purchaseorder_debug.log';


    public function simple_log ($message, array $context = array())
    {
        // check if any handler will handle this message so we can return early and save cycles
        $handlerKey = null;
        $level = $this->loggerType;

        if (!self::$timezone) {
            self::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        $record = array(
            'message' => (string) $message,
            'context' => $context,
            'level' => $level,
            'level_name' => 'DEBUG',
            'channel' => $this->name,
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone)->setTimezone(static::$timezone),
            'extra' => array(),
        );

        $this->handle($record);
    }
}
