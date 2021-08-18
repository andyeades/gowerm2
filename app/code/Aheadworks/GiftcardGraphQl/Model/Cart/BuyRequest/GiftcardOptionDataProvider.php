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
namespace Aheadworks\GiftcardGraphQl\Model\Cart\BuyRequest;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\QuoteGraphQl\Model\Cart\BuyRequest\BuyRequestDataProviderInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class GiftcardOptionDataProvider
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Cart\BuyRequest
 */
class GiftcardOptionDataProvider implements BuyRequestDataProviderInterface
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ArrayManager $arrayManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ArrayManager $arrayManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->arrayManager = $arrayManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $cartItemData): array
    {
        $sku = $this->arrayManager->get('data/sku', $cartItemData);

        try {
            $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Could not find specified product.'));
        }

        return $this->arrayManager->get('aw_giftcard_option', $cartItemData) ?? [];
    }
}
