<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\ThemeOptions\Api\CheckoutAgreements\Data;

/**
 * Interface AgreementInterface
 * @api
 * @since 100.0.2
 */
interface AgreementInterface extends \Magento\CheckoutAgreements\Api\Data\AgreementInterface
{
    const SEPERATE_LINKTEXT = 'seperate_linktext';
    const LINK_TEXT = 'link_text';

    /**
     * Returns the agreement applied mode.
     *
     * @return bool
     */
    public function getSeperateLinktext();

    /**
     * Sets the agreement applied mode.
     *
     * @param bool $seperateLinktext
     * @return $this
     */
    public function setSeperateLinktext($seperateLinktext);


    /**
     * Returns the agreement applied mode.
     *
     * @return string
     */
    public function getLinkText();

    /**
     * Sets the agreement applied mode.
     *
     * @param string $linkText
     * @return $this
     */
    public function setLinkText($linkText);

}
