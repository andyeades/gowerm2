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
namespace Aheadworks\Giftcard\Cron;

use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Aheadworks\Giftcard\Model\Flag;
use Aheadworks\Giftcard\Model\Source\EmailStatus;
use Aheadworks\Giftcard\Model\Source\Entity\Attribute\GiftcardType;
use Aheadworks\Giftcard\Model\Source\Giftcard\EmailTemplate;
use Aheadworks\Giftcard\Model\Source\Giftcard\Status;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;

/**
 * Class DeliveryDateCheck
 *
 * @package Aheadworks\Giftcard\Cron
 */
class DeliveryDateCheck extends CronAbstract
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->isLocked(Flag::AW_GC_DELIVERY_DATE_CHECK_LAST_EXEC_TIME)) {
            return $this;
        }
        $this->processSend();
        $this->setFlagData(Flag::AW_GC_DELIVERY_DATE_CHECK_LAST_EXEC_TIME);
    }

    /**
     * Send Gift Card codes by delivery date
     *
     * @return $this
     */
    private function processSend()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $currentDateTime = $now->format(StdlibDateTime::DATETIME_PHP_FORMAT);
        $this->searchCriteriaBuilder
            ->addFilter(GiftcardInterface::STATE, Status::ACTIVE)
            ->addFilter(GiftcardInterface::EMAIL_TEMPLATE, EmailTemplate::DO_NOT_SEND, 'neq')
            ->addFilter(GiftcardInterface::TYPE, [GiftcardType::VALUE_COMBINED, GiftcardType::VALUE_VIRTUAL], 'in')
            ->addFilter(GiftcardInterface::EMAIL_SENT, [EmailStatus::AWAITING, EmailStatus::FAILED], 'in')
            ->addFilter(GiftcardInterface::DELIVERY_DATE, $currentDateTime, 'checkDeliveryDate');

        $sendNowGiftcards = $this->giftcardRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();

        $giftcards = [];
        foreach ($sendNowGiftcards as $sendNowGiftcard) {
            $giftcards[] = $sendNowGiftcard->getCode();
        }
        $this->giftcardManagement->sendGiftcardByCode($giftcards);
        return $this;
    }
}
