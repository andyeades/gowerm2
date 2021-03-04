<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Elevate\Megamenu\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Elevate\Megamenu\Api\Data\MegamenuInterface;

interface MegamenuRepositoryInterface
{
  /**
   * @param int $id
   * @return \Elevate\Megamenu\Api\Data\MegamenuInterface
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   */
  public function get($id);

  /**
   * @param int $id
   * @return \Elevate\Megamenu\Api\Data\MegamenuInterface
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
   * @return \Elevate\Megamenu\Api\Data\MegamenuInterface
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
   * @param \Elevate\Megamenu\Api\Data\MegamenuInterface $megamenu
   * @return \Elevate\Megamenu\Api\Data\MegamenuInterface
   */
  public function save(MegamenuInterface $megamenu);

  /**
   * @param \Elevate\Megamenu\Api\Data\MegamenuInterface $megamenu
   * @return void
   */
  public function delete(MegamenuInterface $megamenu);

  /**
   * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
   * @return \Elevate\Megamenu\Api\Data\MegamenuSearchResultInterface
   */
  public function getList(SearchCriteriaInterface $searchCriteria);
  /**
   * Creates new Transaction instance.
   *
   * @return \Elevate\Megamenu\Api\Data\MegamenuInterface
   */
  public function create();
}
