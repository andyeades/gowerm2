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
namespace Aheadworks\Giftcard\Model\Source\History\Comment;

/**
 * Class Action
 *
 * @package Aheadworks\Giftcard\Model\Source\History
 */
class Action
{
    /**#@+
     * Comment action values
     */
    const BY_ADMIN = 1;
    const CREATED_BY_ORDER = 2;
    const APPLIED_TO_ORDER = 3;
    const REFUND_GIFTCARD = 4;
    const REIMBURSED_FOR_CANCELLED_ORDER = 5;
    const REIMBURSED_FOR_REFUNDED_ORDER = 6;
    const EXPIRED = 7;
    const DELIVERY_DATE_EMAIL_STATUS = 8;
    const TYPE_CHANGED = 9;
    /**#@-*/
}
