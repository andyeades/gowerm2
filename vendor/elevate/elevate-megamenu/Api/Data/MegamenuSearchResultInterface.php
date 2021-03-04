<?php

namespace Elevate\Megamenu\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface MegamenuSearchResultInterface extends SearchResultsInterface
{
  /**
   * @return \Elevate\Megamenu\Api\Data\MegamenuInterface[]
   */
  public function getItems();

  /**
   * @param \Elevate\Megamenu\Api\Data\MegamenuInterface[] $items
   * @return void
   */
  public function setItems(array $items);
}