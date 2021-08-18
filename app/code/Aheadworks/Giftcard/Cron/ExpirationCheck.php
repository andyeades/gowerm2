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
use Aheadworks\Giftcard\Model\Source\Giftcard\Status;
use Aheadworks\Giftcard\Api\Data\Giftcard\HistoryActionInterface;
use Aheadworks\Giftcard\Model\Source\History\Comment\Action as SourceHistoryCommentAction;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Api\Data\WebsiteInterface;

/**
 * Class ExpirationCheck
 *
 * @package Aheadworks\Giftcard\Cron
 */
class ExpirationCheck extends CronAbstract
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->isLocked(Flag::AW_GC_EXPIRATION_CHECK_LAST_EXEC_TIME)) {
            return $this;
        }
        $this->processExpire();
        $this->setFlagData(Flag::AW_GC_EXPIRATION_CHECK_LAST_EXEC_TIME);
    }

    /**
     * Set expired state to Gift Card
     *
     * @return $this
     */
    private function processExpire()
    {
        foreach ($this->storeManager->getWebsites() as $website) {
            $this->searchCriteriaBuilder
                ->addFilter(GiftcardInterface::STATE, Status::ACTIVE)
                ->addFilter(GiftcardInterface::WEBSITE_ID, $website->getId())
                ->addFilter(GiftcardInterface::EXPIRE_AT, $this->getWebsiteDate($website), 'expired');

            $expiredGiftcards = $this->giftcardRepository
                ->getList($this->searchCriteriaBuilder->create())
                ->getItems();

            foreach ($expiredGiftcards as $expiredGiftcard) {
                /** @var HistoryActionInterface $historyObject */
                $historyObject = $this->historyActionFactory->create();
                $historyObject
                    ->setActionType(SourceHistoryCommentAction::EXPIRED);

                $expiredGiftcard->setCurrentHistoryAction($historyObject);
                $expiredGiftcard->setState(Status::EXPIRED);
                $this->giftcardRepository->save($expiredGiftcard);
            }
        }
        return $this;
    }

    /**
     * Retrieve website date
     *
     * @param WebsiteInterface $website
     * @return string
     */
    private function getWebsiteDate($website)
    {
        $websiteTimezone = $this->localeDate->getConfigTimezone(ScopeInterface::SCOPE_WEBSITE, $website->getCode());
        $now = new \DateTime(null, new \DateTimeZone($websiteTimezone));
        $now->setTimezone(new \DateTimeZone('UTC'));

        return $now->format('Y-m-d');
    }
}
