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

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class AbstractDataProvider
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider
 */
abstract class AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(DataObjectProcessor $dataObjectProcessor)
    {
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Use class reflection on given data interface to build output data array
     *
     * @param SearchResultsInterface $searchResult
     * @param string $objectType
     */
    protected function convertResultItemsToDataArray($searchResult, string $objectType)
    {
        $itemsAsArray = [];
        foreach ($searchResult->getItems() as $item) {
            $itemsAsArray[] = $this->dataObjectProcessor->buildOutputDataArray(
                $item,
                $objectType
            );
        }

        $searchResult->setItems($itemsAsArray);
    }
}
