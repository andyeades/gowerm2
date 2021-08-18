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
 * @package    GiftcardGraphQl
 * @version    1.0.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface DataProviderInterface
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider
 */
interface DataProviderInterface
{
    /**
     * Retrieve data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param int|null $storeId
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId = null);
}
