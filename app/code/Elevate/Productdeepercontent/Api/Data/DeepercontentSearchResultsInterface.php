<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Api\Data;

interface DeepercontentSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Deepercontent list.
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface[]
     */
    public function getItems();

    /**
     * Set deepercontent list.
     * @param \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

