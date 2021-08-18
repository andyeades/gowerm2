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
namespace Aheadworks\Giftcard\Plugin\Model\Order;

use Magento\Sales\Model\Order\Creditmemo;

/**
 * Class CreditmemoPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model\Order
 */
class CreditmemoPlugin
{
    /**
     * @var CreditmemoRepositoryPlugin
     */
    private $creditmemoRepositoryPlugin;

    /**
     * @param CreditmemoRepositoryPlugin $creditmemoRepositoryPlugin
     */
    public function __construct(
        CreditmemoRepositoryPlugin $creditmemoRepositoryPlugin
    ) {
        $this->creditmemoRepositoryPlugin = $creditmemoRepositoryPlugin;
    }

    /**
     * Add Gift Card data to credit memo object
     *
     * @param Creditmemo $subject
     * @param Creditmemo $creditmemo
     * @return Creditmemo
     */
    public function afterAddData($subject, $creditmemo)
    {
        return $this->creditmemoRepositoryPlugin->addGiftcardDataToCreditmemo($creditmemo);
    }

    /**
     * Set allowZeroGrandTotal flag
     *
     * @param Creditmemo $creditmemo
     * @return void
     */
    public function beforeIsValidGrandTotal($creditmemo)
    {
        if ($creditmemo->getExtensionAttributes() && $creditmemo->getExtensionAttributes()->getAwGiftcardCodes()) {
            $creditmemo->setAllowZeroGrandTotal(true);
        }
    }
}
