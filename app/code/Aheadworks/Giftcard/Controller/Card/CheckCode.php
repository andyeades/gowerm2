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
namespace Aheadworks\Giftcard\Controller\Card;

use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Magento\Framework\App\Action\Context;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;
use Magento\Framework\App\Action\Action;
use Aheadworks\Giftcard\Block\Giftcard\Info as GiftcardInfo;

/**
 * Class CheckCode
 *
 * @package Aheadworks\Giftcard\Controller\Card
 */
class CheckCode extends Action
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param Context $context
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        GiftcardRepositoryInterface $giftcardRepository,
        StoreManagerInterface $storeManager,
        Escaper $escaper
    ) {
        $this->giftcardRepository = $giftcardRepository;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * Check Gift Card Code
     *
     * @return string
     */
    public function execute()
    {
        $giftcardCode = $this->getRequest()->getParam('code');
        $websiteId = $this->storeManager->getWebsite()->getId();
        /** @var GiftcardInfo $blockInstance */
        $blockInstance = $this->_view->getLayout()->createBlock(GiftcardInfo::class);
        try {
            /** @var $giftcard GiftcardInterface */
            $giftcard = $this->giftcardRepository->getByCode($giftcardCode, $websiteId);
            $blockInstance->setGiftcard($giftcard);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(
                __('Gift Card code "%1" is not valid', $this->escaper->escapeHtml($giftcardCode))
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->getResponse()->appendBody($blockInstance->toHtml());
    }
}
