<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model;

use Magento\Framework\Flag as FrameworkFlag;

/**
 * Class Flag
 *
 * @package Aheadworks\Giftcard\Model
 */
class Flag extends FrameworkFlag
{
    /**#@+
     * Constants for Gift Card cron flags
     */
    const AW_GC_EXPIRATION_CHECK_LAST_EXEC_TIME = 'aw_gc_expiration_check_last_exec_time';
    const AW_GC_DELIVERY_DATE_CHECK_LAST_EXEC_TIME = 'aw_gc_delivery_date_check_last_exec_time';
    const AW_GC_EXPIRATION_REMINDER_LAST_EXEC_TIME = 'aw_gc_expiration_reminder_last_exec_time';
    /**#@-*/

    /**
     * Setter for flag code
     * @codeCoverageIgnore
     *
     * @param string $code
     * @return $this
     */
    public function setGiftcardFlagCode($code)
    {
        $this->_flagCode = $code;
        return $this;
    }
}
