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

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * PoolCode CRUD interface
 * @api
 */
interface PoolCodeRepositoryInterface
{
    /**
     * Retrieve code by id
     *
     * @param int $codeId
     * @return \Aheadworks\Giftcard\Api\Data\Pool\CodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($codeId);

    /**
     * Retrieve pool codes matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Giftcard\Api\Data\Pool\CodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete pool code
     *
     * @param \Aheadworks\Giftcard\Api\Data\Pool\CodeInterface $code
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Giftcard\Api\Data\Pool\CodeInterface $code);

    /**
     * Delete pool code by ID
     *
     * @param int $codeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($codeId);
}
