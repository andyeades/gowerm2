<?php
namespace Elevate\Discontinuedproducts\Plugin\Block\ConfigurableProduct\Product\View\Type;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as Subject;
use Magento\Framework\Serialize\Serializer\Json;

class Configurable
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var array
     */
    private $allProducts = [];

    /**
     * @var Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var StockConfigurationInterface
     */
    private $stockConfiguration;

    /**
     * Configurable constructor.
     *
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     */
    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        StockConfigurationInterface $stockConfiguration
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->stockRegistry = $stockRegistry;
        $this->moduleManager = $moduleManager;
        $this->jsonEncoder = $jsonEncoder;
        $this->registry = $registry;
        $this->stockConfiguration = $stockConfiguration;
    }

    public function aroundGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        \Closure $proceed
    ) {
        $config = $proceed();
        $config = $this->jsonSerializer->unserialize($config);
        $productsCollection = $subject->getAllowProducts();
        $stockInfo = array();
        foreach ($productsCollection as $product) {
            $productId = $product->getId();
            $stockItem = $this->stockRegistry->getStockItem($product->getId());

            $stockInfo[$productId] = array(
                "is_in_stock" => (int)$stockItem->getIsInStock(),
                "stockQty" => intval($stockItem->getIsInStock()),
                'product_discontinued' => (int)intval($product->getData('product_discontinued'))
            );
        }

        $config['stockInfo'] = $stockInfo;
        return $this->jsonSerializer->serialize($config);
    }

    /**
     * @param $subject
     * @return mixed
     */
    public function beforeGetAllowProducts($subject)
    {
        if (!$subject->hasAllowProducts() && $this->stockConfiguration->isShowOutOfStock()) {
        /** @var Product $product */
        $product = $subject->getProduct();
        $allowProducts = [];
        $usedProducts = $product->getTypeInstance(true)
                                ->getUsedProducts($product);
        /** @var Product $usedProduct */
        foreach ($usedProducts as $usedProduct) {
            if ($usedProduct->getStatus() == Status::STATUS_ENABLED) {
                $allowProducts[] = $usedProduct;
            }
        }
        $subject->setAllowProducts($allowProducts);
    }
        return $subject->getData('allow_products');



        foreach ($subject->getData('allow_products') as $product) {
            echo '<pre>';
            print_r($product->getData());
            echo '</pre>';
        }


        return $subject->getData('allow_products');

    }

    /**
     * @param $subject
     * @param $html
     * @return string
     */
    public function afterFetchView($subject, $html)
    {
        $configurableLayout = ['product.info.options.configurable', 'product.info.options.swatches'];
        if (in_array($subject->getNameInLayout(), $configurableLayout)
            && !$this->moduleManager->isEnabled('Amasty_Stockstatus')
            && !$this->registry->registry('amasty_xnotif_initialization')
        ) {
            $this->registry->register('amasty_xnotif_initialization', 1);

            /*move creating code to Amasty\Xnotif\Plugins\ConfigurableProduct\Data */
            $aStockStatus = $this->registry->registry('amasty_xnotif_data');
            $aStockStatus['changeConfigurableStatus'] = true;
            $data = $this->jsonEncoder->encode($aStockStatus);

            $html
                = '<script type="text/x-magento-init">
                    {
                        ".product-options-wrapper": {
                                    "amnotification": {
                                        "xnotif": ' . $data . '
                                    }
                         }
                    }
                   </script>' . $html;
        }

        return $html;
    }

    /**
     * @param $subject
     * @return mixed
     */
    private function getAllProducts($subject)
    {
        $productId = $subject->getProduct()->getId();

        if (!isset($this->allProducts[$productId])) {
            $products = [];
            $allProducts = $subject->getProduct()->getTypeInstance(true)
                                   ->getUsedProducts($subject->getProduct());
            foreach ($allProducts as $product) {
                if ($product->getStatus() == Status::STATUS_ENABLED) {
                    $products[] = $product;
                }
            }
            $this->allProducts[$productId] = $products;
        }

        return $this->allProducts[$productId];
    }
}
