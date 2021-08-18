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
use Aheadworks\Giftcard\Api\Data\Pool\CodeInterface;
use Aheadworks\Giftcard\Api\PoolCodeRepositoryInterface;

/**
 * Class PoolCode
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider
 */
class PoolCode extends AbstractDataProvider
{
    /**
     * @var PoolCodeRepositoryInterface
     */
    private $poolCodeRepository;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param PoolCodeRepositoryInterface $poolCodeRepository
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        PoolCodeRepositoryInterface $poolCodeRepository
    ) {
        parent::__construct($dataObjectProcessor);
        $this->poolCodeRepository = $poolCodeRepository;
    }

    /**
     * @inheritdoc
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId = null)
    {
        $result = $this->poolCodeRepository->getList($searchCriteria);
        $this->convertResultItemsToDataArray($result, CodeInterface::class);

        return $result;
    }
}
