<?php
declare(strict_types=1);

namespace Firebear\ConfigurableProducts\Block\Product\Renderer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View\AbstractView;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class Any
 * @package Firebear\ConfigurableProducts\Block\Product\Renderer
 */
class Any extends AbstractView
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param Context $context
     * @param ArrayUtils $arrayUtils
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->productRepository = $productRepository;

        parent::__construct($context, $arrayUtils, $data);
    }

    /**
     * Produce and return block's html output
     *
     * @codeCoverageIgnore
     * @return string
     */
    public function toHtml()
    {
        $this->setTemplate('Firebear_ConfigurableProducts::product/view/type/options/default.phtml');

        return parent::toHtml();
    }

    /**
     * Composes configuration for js
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $store = $this->getCurrentStore();
        $productId = $this->_request->getParam('id');
        if ('checkout_cart_configure' === $this->_request->getFullActionName()) {
            $productId = $this->_request->getParam('product_id');
        }
        $currentProduct = null;
        if ($productId) {
            $currentProduct = $this->productRepository->getById($productId);
        }

        $regularPrice = $this->getProduct()->getPriceInfo()->getPrice('regular_price');
        $finalPrice = $this->getProduct()->getPriceInfo()->getPrice('final_price');

        $config = [
            'template' => str_replace('%s', '<%- data.price %>', $store->getCurrentCurrency()->getOutputFormat()),
            'prices' => [
                'oldPrice' => [
                    'amount' => $this->_registerJsPrice($regularPrice->getAmount()->getValue()),
                ],
                'basePrice' => [
                    'amount' => $this->_registerJsPrice(
                        $finalPrice->getAmount()->getBaseAmount()
                    ),
                ],
                'finalPrice' => [
                    'amount' => $this->_registerJsPrice($finalPrice->getAmount()->getValue()),
                ],
            ],
            'productId' => $this->getProduct()->getId(),
            'chooseText' => __('Choose an Option...'),
            'images' => [],
            'index' => [],
        ];
        if ($currentProduct && $currentProduct->getTypeId() == 'bundle') {
            $currentTime = strtotime('now');
            $specialFromDate = !$currentProduct->getSpecialFromDate() ?: strtotime($currentProduct->getSpecialFromDate());
            $specialToDatePrice = !$currentProduct->getSpecialToDate() ?: strtotime($currentProduct->getSpecialToDate());
            $specialPrice = $currentProduct->getSpecialPrice();
            if (($specialFromDate <= $currentTime && $currentTime < $specialToDatePrice) || !$specialToDatePrice) {
                $config['special_price'] = ($specialPrice > 0) ? $specialPrice : false;
            } else {
                $config['special_price'] = false;
            }
        }

        if ($this->getProduct()->hasPreconfiguredValues() && !empty($attributesData['defaultValues'])) {
            $config['defaultValues'] = $attributesData['defaultValues'];
        }

        return json_encode($config);
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * Replace ',' on '.' for js
     *
     * @param float $price
     *
     * @return string
     */
    protected function _registerJsPrice($price)
    {
        return str_replace(',', '.', $price);
    }

    public function getOptionBlock()
    {
        $optionBlock = $this->getChildBlock('configurableOptions' . $this->getOption()->getId());
        $optionBlock->setProduct($this->getProduct());

        return $optionBlock;
    }
}
