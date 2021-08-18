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
use Magento\Framework\Reflection\DataObjectProcessor;
use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;

/**
 * Class GiftCard
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider
 */
class GiftCard extends AbstractDataProvider
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param GiftcardRepositoryInterface $giftcardRepository
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        GiftcardRepositoryInterface $giftcardRepository
    ) {
        parent::__construct($dataObjectProcessor);
        $this->giftcardRepository = $giftcardRepository;
    }

    /**
     * @inheritdoc
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId = null)
    {
        $result = $this->giftcardRepository->getList($searchCriteria);
        $this->convertResultItemsToDataArray($result, GiftcardInterface::class);

        return $result;
    }
}
