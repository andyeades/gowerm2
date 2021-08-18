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
namespace Aheadworks\Giftcard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Gift Card search results
 * @api
 */
interface GiftcardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Gift Card list
     *
     * @return \Aheadworks\Giftcard\Api\Data\GiftcardInterface[]
     */
    public function getItems();

    /**
     * Set Gift Card list
     *
     * @param \Aheadworks\Giftcard\Api\Data\GiftcardInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
