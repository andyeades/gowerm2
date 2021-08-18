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
 * Interface for pool search results
 * @api
 */
interface PoolSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pool list
     *
     * @return \Aheadworks\Giftcard\Api\Data\PoolInterface[]
     */
    public function getItems();

    /**
     * Set pool list
     *
     * @param \Aheadworks\Giftcard\Api\Data\PoolInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
