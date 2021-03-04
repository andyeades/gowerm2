<?php

namespace Elevate\ProductIcons\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProducticonsSearchResultInterface extends SearchResultsInterface
{
  /**
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsInterface[]
   */
  public function getItems();

  /**
   * @param \Elevate\ProductIcons\Api\Data\ProducticonsInterface[] $items
   * @return void
   */
  public function setItems(array $items);
}