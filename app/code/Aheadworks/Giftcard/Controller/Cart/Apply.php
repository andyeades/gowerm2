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
namespace Aheadworks\Giftcard\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Aheadworks\Giftcard\Api\GiftcardCartManagementInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Escaper;

/**
 * Class Apply
 *
 * @package Aheadworks\Giftcard\Controller\Cart
 */
class Apply extends Action
{
    /**
     * @var GiftcardCartManagementInterface
     */
    private $giftcardCartManagement;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var array
     */
    private $redirectToPath = [
        'multishipping' => 'multishipping/checkout/billing'
    ];

    /**
     * @param Context $context
     * @param GiftcardCartManagementInterface $giftcardCartManagement
     * @param CheckoutSession $checkoutSession
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        GiftcardCartManagementInterface $giftcardCartManagement,
        CheckoutSession $checkoutSession,
        Escaper $escaper
    ) {
        $this->giftcardCartManagement = $giftcardCartManagement;
        $this->checkoutSession = $checkoutSession;
        $this->escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * Apply Gift Card code on cart page
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $giftcardCode = $this->getRequest()->getParam('code');
        try {
            if ($this->giftcardCartManagement->set($this->checkoutSession->getQuoteId(), $giftcardCode)) {
                $this->messageManager->addSuccessMessage(
                    __('Gift Card code "%1" was successfully applied', $this->escaper->escapeHtml($giftcardCode))
                );
            }
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Cannot apply Gift Card code'));
        }

        $redirectToRequest = $this->getRequest()->getParam('redirect_to');
        $redirectTo = $redirectToRequest && isset($this->redirectToPath[$redirectToRequest])
            ? $this->redirectToPath[$redirectToRequest]
            : 'checkout/cart';

        return $this->_redirect($redirectTo);
    }
}
