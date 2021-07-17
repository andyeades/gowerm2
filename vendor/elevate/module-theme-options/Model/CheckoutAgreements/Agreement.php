<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Themeoptions\Model\CheckoutAgreements;

use Elevate\Themeoptions\Api\CheckoutAgreements\Data\AgreementInterface;

class Agreement extends \Magento\CheckoutAgreements\Model\Agreement implements AgreementInterface
{

    /**
     * @inheritdoc
     */
    public function getSeperateLinktext()
    {
        return $this->getData(self::SEPERATE_LINKTEXT);
    }

    /**
     * @inheritdoc
     */
    public function setSeperateLinktext($seperateLinktext)
    {
        return $this->setData(self::SEPERATE_LINKTEXT, $seperateLinktext);
    }

    /**
     * @inheritdoc
     */
    public function getLinkText()
    {
        return $this->getData(self::LINK_TEXT);
    }

    /**
     * @inheritdoc
     */
    public function setLinkText($linkText)
    {
        return $this->setData(self::LINK_TEXT, $linkText);
    }

}
