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
namespace Aheadworks\Giftcard\Api\Data\Giftcard;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface QuoteInterface
 * @api
 */
interface QuoteInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const GIFTCARD_ID = 'giftcard_id';
    const GIFTCARD_CODE = 'giftcard_code';
    const QUOTE_ID = 'quote_id';
    const GIFTCARD_BALANCE = 'giftcard_balance';
    const BASE_GIFTCARD_BALANCE_USED = 'base_giftcard_balance_used';
    const GIFTCARD_PRODUCT_ID = 'giftcard_product_id';
    const GIFTCARD_BALANCE_USED = 'giftcard_balance_used';
    const BASE_GIFTCARD_AMOUNT = 'base_giftcard_amount';
    const GIFTCARD_AMOUNT = 'giftcard_amount';
    const IS_REMOVE = 'is_remove';
    const IS_INVALID = 'is_invalid';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get Gift Card id
     *
     * @return int
     */
    public function getGiftcardId();

    /**
     * Set Gift Card id
     *
     * @param int $giftcardId
     * @return $this
     */
    public function setGiftcardId($giftcardId);

    /**
     * Set Gift Card code
     *
     * @param string $giftcardCode
     * @return $this
     */
    public function setGiftcardCode($giftcardCode);

    /**
     * Get Gift Card code
     *
     * @return string
     */
    public function getGiftcardCode();

    /**
     * Get quote id
     *
     * @return int
     */
    public function getQuoteId();

    /**
     * Set quote id
     *
     * @param int $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * Get Gift Card balance
     *
     * @return float
     */
    public function getGiftcardBalance();

    /**
     * Set Gift Card balance
     *
     * @param float $balance
     * @return $this
     */
    public function setGiftcardBalance($balance);

    /**
     * Get Gift Card balance used
     *
     * @return float
     */
    public function getGiftcardBalanceUsed();

    /**
     * Set Gift Card balance used
     *
     * @param float $balanceUsed
     * @return $this
     */
    public function setGiftcardBalanceUsed($balanceUsed);

    /**
     * Get base Gift Card balance used
     *
     * @return float
     */
    public function getBaseGiftcardBalanceUsed();

    /**
     * Set base Gift Card balance used
     *
     * @param float $baseBalanceUsed
     * @return $this
     */
    public function setBaseGiftcardBalanceUsed($baseBalanceUsed);

    /**
     * Get base Gift Card amount
     *
     * @return float
     */
    public function getBaseGiftcardAmount();

    /**
     * Set base Gift Card amount
     *
     * @param float $amount
     * @return $this
     */
    public function setBaseGiftcardAmount($amount);

    /**
     * Get Gift Card amount
     *
     * @return float
     */
    public function getGiftcardAmount();

    /**
     * Set Gift Card amount
     *
     * @param float $amount
     * @return $this
     */
    public function setGiftcardAmount($amount);

    /**
     * Check is remove Gift Card or not
     *
     * @return bool
     */
    public function isRemove();

    /**
     * Set is remove flag
     *
     * @param bool $isRemove
     * @return $this
     */
    public function setIsRemove($isRemove);

    /**
     * Check is Gift Card invalid or not
     *
     * @return bool
     */
    public function isInvalid();

    /**
     * Set is Gift Card invalid flag
     *
     * @param bool $isInvalid
     * @return $this
     */
    public function setIsInvalid($isInvalid);

    /**
     * Get Gift Card product id
     *
     * @return int|null
     */
    public function getGiftcardProductId();

    /**
     * Set is Gift Card product id
     *
     * @param int $productId
     * @return $this
     */
    public function setGiftcardProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Giftcard\Api\Data\Giftcard\QuoteExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Giftcard\Api\Data\Giftcard\QuoteExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Giftcard\Api\Data\Giftcard\QuoteExtensionInterface $extensionAttributes
    );
}
