<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeepercontentRepositoryInterface
{

    /**
     * Save Deepercontent
     * @param \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface $deepercontent
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface $deepercontent
    );

    /**
     * Retrieve Deepercontent
     * @param string $deepercontentId
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($deepercontentId);

    /**
     * Retrieve Deepercontent matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Deepercontent
     * @param \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface $deepercontent
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface $deepercontent
    );

    /**
     * Delete Deepercontent by ID
     * @param string $deepercontentId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deepercontentId);
}

