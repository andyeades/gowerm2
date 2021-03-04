<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Elevate\ProductIcons\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Elevate\ProductIcons\Api\Data\ProducticonsInterface;

interface ProducticonsRepositoryInterface
{
  /**
   * @param int $id
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsInterface
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   */
  public function get($id);

  /**
   * @param int $id
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsInterface
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   */
  public function getById($id);

  /**
   * @param int $id
   * @return bool Will returned True if deleted
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   * @throws \Magento\Framework\Exception\StateException
   */
  public function deleteById($id);


  /**
   * @param int $id
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsInterface
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   */
  public function getByEntityId($id);


  /**
   * @param int $id
   * @return bool Will returned True if deleted
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   * @throws \Magento\Framework\Exception\StateException
   */
  public function deleteByEntityId($id);

  /**
   * @param \Elevate\ProductIcons\Api\Data\ProducticonsInterface $producticons
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsInterface
   */
  public function save(ProducticonsInterface $producticons);

  /**
   * @param \Elevate\ProductIcons\Api\Data\ProducticonsInterface $producticons
   * @return void
   */
  public function delete(ProducticonsInterface $producticons);

  /**
   * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsSearchResultInterface
   */
  public function getList(SearchCriteriaInterface $searchCriteria);
  /**
   * Creates new Transaction instance.
   *
   * @return \Elevate\ProductIcons\Api\Data\ProducticonsInterface
   */
  public function create();
}
