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
use Aheadworks\Giftcard\Api\Data\PoolInterface;
use Aheadworks\Giftcard\Api\PoolRepositoryInterface;

/**
 * Class Pool
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider
 */
class Pool extends AbstractDataProvider
{
    /**
     * @var PoolRepositoryInterface
     */
    private $poolRepository;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param PoolRepositoryInterface $poolRepository
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        PoolRepositoryInterface $poolRepository
    ) {
        parent::__construct($dataObjectProcessor);
        $this->poolRepository = $poolRepository;
    }

    /**
     * @inheritdoc
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId = null)
    {
        $result = $this->poolRepository->getList($searchCriteria);
        $this->convertResultItemsToDataArray($result, PoolInterface::class);

        return $result;
    }
}
