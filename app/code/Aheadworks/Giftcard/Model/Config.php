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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Aheadworks\Giftcard\Model
 */
class Config
{
    /**#@+
     * Constants for config path
     */
    const XML_PATH_GIFTCARD_EXPIRE_DAYS = 'aw_giftcard/general/expire_days';
    const XML_PATH_EMAIL_SENDER = 'aw_giftcard/email/sender';
    const XML_PATH_GIFTCARD_CODE_LENGTH = 'aw_giftcard/code_pattern/code_length';
    const XML_PATH_GIFTCARD_CODE_FORMAT = 'aw_giftcard/code_pattern/code_format';
    const XML_PATH_GIFTCARD_CODE_PREFIX = 'aw_giftcard/code_pattern/code_prefix';
    const XML_PATH_GIFTCARD_CODE_SUFFIX = 'aw_giftcard/code_pattern/code_suffix';
    const XML_PATH_GIFTCARD_CODE_DASH_EVERY_X_CHARACTERS = 'aw_giftcard/code_pattern/dash_every_x_characters';
    const XML_PATH_GIFTCARD_ADVANCED_TAX = 'aw_giftcard/advanced/tax';
    const XML_PATH_GENERAL_LOCALE_CODE = 'general/locale/code';
    const XML_PATH_GENERAL_LOCALE_TIMEZONE = 'general/locale/timezone';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Gift Card expire days
     *
     * @param int|null $websiteId
     * @return int
     */
    public function getGiftcardExpireDays($websiteId = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_EXPIRE_DAYS,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get email sender
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEmailSender($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get email sender name
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEmailSenderName($storeId = null)
    {
        $sender = $this->getEmailSender($storeId);
        return $this->scopeConfig->getValue(
            'trans_email/ident_' . $sender . '/name',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Gift Card code length
     *
     * @param int|null $websiteId
     * @return int
     */
    public function getGiftcardCodeLength($websiteId = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_LENGTH,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code format
     *
     * @param string|null $websiteId
     * @return int
     */
    public function getGiftcardCodeFormat($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_FORMAT,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code prefix
     *
     * @param string|null $websiteId
     * @return int
     */
    public function getGiftcardCodePrefix($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_PREFIX,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code suffix
     *
     * @param string|null $websiteId
     * @return int
     */
    public function getGiftcardCodeSuffix($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_SUFFIX,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code dash every X characters
     *
     * @param int|null $websiteId
     * @return int
     */
    public function getGiftcardCodeDashAtEvery($websiteId = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_DASH_EVERY_X_CHARACTERS,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Need to include tax to giftcard balance
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function needToIncludeTaxToGiftcardBalance($websiteId = null)
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_ADVANCED_TAX,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
