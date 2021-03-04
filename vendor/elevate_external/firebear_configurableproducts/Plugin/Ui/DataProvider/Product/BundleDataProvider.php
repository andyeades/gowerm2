<?php

namespace Firebear\ConfigurableProducts\Plugin\Ui\DataProvider\Product;

use Firebear\ConfigurableProducts\Helper\Data as IcpHelper;
use Magento\Catalog\Model\Product\Option as ProductOption;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class BundleDataProvider
{
    /**
     * @var IcpHelper
     */
    private $icpHelper;

    /**
     * @var ProductOption
     */
    protected $productOption;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * BundleDataProvider constructor.
     * @param IcpHelper $icpHelper
     * @param ProductOption $productOption
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        IcpHelper $icpHelper,
        ProductOption $productOption,
        ProductRepositoryInterface $productRepository
    ) {
        $this->icpHelper = $icpHelper;
        $this->productOption = $productOption;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Magento\Bundle\Ui\DataProvider\Product\BundleDataProvider $subject
     * @param callable $proceed
     * @return array
     */
    public function aroundGetData(
        \Magento\Bundle\Ui\DataProvider\Product\BundleDataProvider $subject,
        callable $proceed
    ) {
        if ($this->icpHelper->getGeneralConfig('bundle_options/enable')) {
            if (!$subject->getCollection()->isLoaded()) {
                $subject->getCollection()->addAttributeToFilter(
                    'type_id',
                    [
                        'simple'       => 'simple',
                        'virtual'      => 'virtual',
                        'configurable' => 'configurable'
                    ]
                );

                $subject->getCollection()->addStoreFilter(
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );
                $subject->getCollection()->load();
            }
            $useCustomOptions = $this->icpHelper->getGeneralConfig('bundle_options/use_custom_options_for_variations_in_bundle');
            $items = $subject->getCollection()->toArray();
                foreach ($items as $id => $item) {
                    try {
                        $product = $this->productRepository->getById($item['entity_id']);
                    } catch (NoSuchEntityException $e) {
                        continue;
                    }
                    if (!$useCustomOptions) {
                        $customOptions = $this->productOption->getProductOptions($product);
                        if ($customOptions) {
                            unset($items[$id]);
                            continue;
                        }
                    }
                    if ($item['type_id'] == 'configurable') {
                        $childProducts = $product->getTypeInstance()->getUsedProducts($product);
                        foreach ($childProducts as $child) {
                            $customOptions = $this->productOption->getProductOptions($child);
                            if ($customOptions) {
                                unset($items[$id]);
                                break;
                            }
                        }
                    }
                }

            return [
                'totalRecords' => $subject->getCollection()->getSize(),
                'items'        => array_values($items),
            ];
        } else {
            return $proceed();
        }
    }
}
