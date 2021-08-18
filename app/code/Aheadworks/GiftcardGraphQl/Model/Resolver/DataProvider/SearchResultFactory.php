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

use Magento\Framework\ObjectManagerInterface;

/**
 * Class SearchResultFactory
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\DataProvider
 */
class SearchResultFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create SearchResult
     *
     * @param int $totalCount
     * @param array $items
     * @return SearchResult
     */
    public function create(int $totalCount, array $items) : SearchResult
    {
        return $this->objectManager->create(
            SearchResult::class,
            ['totalCount' => $totalCount, 'items' => $items]
        );
    }
}
