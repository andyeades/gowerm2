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
namespace Aheadworks\Giftcard\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Interface GiftcardCartManagementInterface
 * @api
 */
interface GiftcardCartManagementInterface
{
    /**
     * Retrieve information for Gift Card codes in specified cart
     *
     * @param int $cartId
     * @param bool $activeQuote
     * @return \Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface[]
     * @throws NoSuchEntityException Cart $cartId doesn't contain products
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function get($cartId, $activeQuote = true);

    /**
     * Add Gift Card code to specified cart
     *
     * @param int $cartId
     * @param string $giftcardCode
     * @param bool $activeQuote
     * @return boolean
     * @throws CouldNotSaveException The specified Gift Card code not be added
     * @throws NoSuchEntityException Cart $cartId doesn't contain products
     * @throws NoSuchEntityException The specified Gift Card code is not valid
     * @throws LocalizedException The specified Gift Card code deactivated
     * @throws LocalizedException The specified Gift Card code expired
     * @throws LocalizedException The specified Gift Card code used
     */
    public function set($cartId, $giftcardCode, $activeQuote = true);

    /**
     * Delete Gift Card code from specified cart
     *
     * @param int $cartId
     * @param string $giftcardCode
     * @param bool $activeQuote
     * @return boolean
     * @throws NoSuchEntityException Cart $cartId doesn't contain products
     * @throws NoSuchEntityException The specified Gift Card code is not valid
     * @throws CouldNotDeleteException The specified Gift Card code could not be deleted
     */
    public function remove($cartId, $giftcardCode, $activeQuote = true);
}
