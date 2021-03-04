<?php
namespace Firebear\ConfigurableProducts\Plugin\Pricing\Price;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Firebear\ConfigurableProducts\Model\Product\Defaults;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class FinalPrice
{
    /**
     * @var \Firebear\ConfigurableProducts\Model\Product\Defaults
     */
    protected $defaults;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * FinalPrice constructor.
     * @param \Firebear\ConfigurableProducts\Model\Product\Defaults $defaults
     * @param ProductRepositoryInterface $productRepository
     * @param RequestInterface $_request
     */
    public function __construct(
        Defaults $defaults,
        ProductRepositoryInterface $productRepository,
        RequestInterface $_request
    ) {
        $this->defaults = $defaults;
        $this->productRepository = $productRepository;
        $this->_request = $_request;
    }

    public function afterGetValue(\Magento\ConfigurableProduct\Pricing\Price\FinalPrice $subject, $result)
    {
        $isCatalogProductView = $this->_request->getControllerName() == 'product';
        $defaultProductId = $this->_request->getParam('id');
        if (!$defaultProductId || $defaultProductId == $subject->getProduct()->getId()) {
            $defaultProductId = $this->defaults->getDefaultProductId($subject->getProduct());
        }
        try {
            if ($defaultProductId && $isCatalogProductView) {
                $currentProductType = $this->productRepository->get($subject->getProduct()->getSku())->getTypeId();
                if ($currentProductType == 'simple' ||
                    $this->_request->getParam('id') == $subject->getProduct()->getId()) {
                    $defaultProduct = $this->productRepository->getById($defaultProductId);
                    if ($defaultProduct && $defaultProduct->getTypeId() !== 'configurable') {
                        $currentTime = strtotime('now');
                        $specialFromDate = strtotime($defaultProduct->getSpecialFromDate());
                        $specialToDatePrice = strtotime($defaultProduct->getSpecialToDate());
                        $specialPrice = $defaultProduct->getSpecialPrice();
                        if ((($specialFromDate <= $currentTime && $currentTime < $specialToDatePrice) || !$specialToDatePrice)
                            && $specialPrice > 0) {
                            $result = (float)$defaultProduct->getSpecialPrice();
                        } else {
                            $result = (float)$defaultProduct->getPrice();
                        }
                    }
                }
            }
        } catch (NoSuchEntityException $e) {
            return $result;
        }
        return $result;
    }
}
