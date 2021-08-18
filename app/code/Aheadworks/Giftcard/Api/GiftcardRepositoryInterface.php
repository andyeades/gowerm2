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
 * Giftcard CRUD interface
 * @api
 */
interface GiftcardRepositoryInterface
{
    /**
     * Save giftcard
     *
     * @param \Aheadworks\Giftcard\Api\Data\GiftcardInterface $giftcard
     * @return \Aheadworks\Giftcard\Api\Data\GiftcardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Giftcard\Api\Data\GiftcardInterface $giftcard);

    /**
     * Retrieve Gift Card by id
     *
     * @param int $giftcardId
     * @return \Aheadworks\Giftcard\Api\Data\GiftcardInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($giftcardId);

    /**
     * Retrieve Gift Card by code
     *
     * @param string $giftcardCode
     * @param int|null $websiteId
     * @return \Aheadworks\Giftcard\Api\Data\GiftcardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByCode($giftcardCode, $websiteId = null);

    /**
     * Retrieve giftcards matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Giftcard\Api\Data\GiftcardSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete giftcard
     *
     * @param \Aheadworks\Giftcard\Api\Data\GiftcardInterface $giftcard
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Giftcard\Api\Data\GiftcardInterface $giftcard);

    /**
     * Delete giftcard by ID
     *
     * @param int $giftcardId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($giftcardId);
}
