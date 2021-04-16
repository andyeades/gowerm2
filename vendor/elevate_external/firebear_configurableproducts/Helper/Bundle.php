<?php

namespace Firebear\ConfigurableProducts\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Firebear\ConfigurableProducts\Model\Bundle\Model\Product\Type\Configurable;

class Bundle extends \Magento\Bundle\Helper\Catalog\Product\Configuration
{
    private $productModel = null;
    private $eavConfig = null;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    
    /**
     * @var Configurable
     */
    private $typeConfigurable = null;

    /**
     * @var \Magento\Catalog\Model\Product\Option
     */
    private $optionModel;

    /**
     * Bundle helper constructor.
     *
     * @param \Magento\Framework\App\Helper\Context         $context
     * @param \Magento\Catalog\Helper\Product\Configuration $productConfiguration
     * @param \Magento\Framework\Pricing\Helper\Data        $pricingHelper
     * @param \Magento\Framework\Escaper                    $escaper
     * @param \Magento\Catalog\Model\Product                $productModel
     * @param \Magento\Eav\Model\Config                     $eavConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Helper\Product\Configuration $productConfiguration,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Model\Product $productModel,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Option $optionModel,
        Configurable $typeConfigurable,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productConfiguration = $productConfiguration;
        $this->pricingHelper = $pricingHelper;
        $this->escaper = $escaper;
        $this->optionModel = $optionModel;

        $this->productModel = $productModel;
        $this->eavConfig = $eavConfig;
        $this->typeConfigurable = $typeConfigurable;
        $this->productRepository = $productRepository;

        parent::__construct($context, $productConfiguration, $pricingHelper, $escaper);
    }

    /**
     * Get bundled selections (slections-products collection)
     *
     * Returns array of options objects.
     * Each option object will contain array of selections objects
     *
     * @param ItemInterface $item
     *
     * @return array
     */
    public function getBundleOptions(ItemInterface $item)
    {
        $options = [];
        $product = $item->getProduct();

        /** @var \Magento\Bundle\Model\Product\Type $typeInstance */
        $typeInstance = $product->getTypeInstance();

        // get bundle options
        $optionsQuoteItemOption = $item->getOptionByCode('bundle_option_ids');

        $bundleOptionsIds = $optionsQuoteItemOption ? json_decode($optionsQuoteItemOption->getValue(), true) : [];
              $is_backorder = false;
        if ($bundleOptionsIds) {
            /** @var \Magento\Bundle\Model\ResourceModel\Option\Collection $optionsCollection */
            $optionsCollection = $typeInstance->getOptionsByIds($bundleOptionsIds, $product);

            // get and add bundle selections collection
            $selectionsQuoteItemOption = $item->getOptionByCode('bundle_selection_ids');

            $bundleSelectionIds = json_decode($selectionsQuoteItemOption->getValue(), true);

            if (!empty($bundleSelectionIds)) {
                $selectionsCollection = $typeInstance->getSelectionsByIds($bundleSelectionIds, $product);

                $bundleOptions = $optionsCollection->appendSelections($selectionsCollection, true);
                    $max_lead_time = 0;
                foreach ($bundleOptions as $bundleOption) {
                    $bundleOptionHtml = $this->buildOptionData($bundleOption, $item);
                               if(isset($bundleOptionHtml['backorder']) && $bundleOptionHtml['backorder'] == 'yes'){
                                        $is_backorder = true;
                               
                               }
                    
                             
                       

                        if($bundleOptionHtml['leadtime'] > $max_lead_time){
                          $max_lead_time = $bundleOptionHtml['leadtime'];
                        
                        } 
                              
                    if (isset($bundleOptionHtml['value']) && $bundleOptionHtml['value']) {
                        $options[] = $bundleOptionHtml;
                    }
                
                }
              
                    
            if($max_lead_time > 0){
                $maxValueInDaysValue = '';
            $leadtimefloor = floor($max_lead_time % 5);

if ($leadtimefloor == 0) {
    if ($max_lead_time == 5) {
        $maxValueInDaysValue = floor($max_lead_time / 5) . ' Week';
    } else {
        $maxValueInDaysValue = floor($max_lead_time / 5) . ' Weeks';
    }
} else {
    if (floor($max_lead_time / 5) == 0) {
        $maxValueInDaysValue = floor($max_lead_time % 5) . ' Working Days';
    } else {
        $maxValueInDaysValue = (floor($max_lead_time / 5)+1) . ' Weeks ';
    }
}


             if($is_backorder){
  $maxValueInDaysValue = 'Available on request';
 }

                   $options[] = array('label' => 'Lead Time','value' => $maxValueInDaysValue,'has_html' => '1');    
            }
            
                
            }
        }

        return $options;
    }

    /**
     * @param               $bundleOption
     * @param               $bundleSelection
     * @param ItemInterface $item
     *
     * @return string
     */
    private function buildSelectionData($bundleOption, $bundleSelection, ItemInterface $item)
    {
        $product = $item->getProduct();

        $qty = $this->getSelectionQty($product, $bundleSelection->getSelectionId()) * 1;
        $itemChildren = $item->getChildren();
        if ($itemChildren) {
            foreach ($item->getChildren() as $childItem) {
                if ($item->getProduct()->getTypeId() == 'bundle' &&
                    $childItem->getProduct()->getTypeId() == 'configurable') {
                    $selectedVariation = $this->productRepository->get($childItem->getSku());
                    $useDecimal = $childItem->getQtyOptions()[$selectedVariation->getId()]->getIsQtyDecimal();
                    if (!$useDecimal) {
                        $qty = floor($qty);
                    }
                }
            }
        }

        $optionHtml = '';

        $serializedBuyRequest = $item->getOptionByCode('info_buyRequest');
        $buyRequest           = json_decode($serializedBuyRequest->getValue(), true);

        $optionPrice = $this->pricingHelper->currency(
            $this->getSelectionFinalPrice($item, $bundleSelection)
        );

        if ($bundleSelection->getTypeId() == 'configurable') {
            if (isset($buyRequest['only_for_checkbox_bundle'][$bundleOption->getId()][$bundleSelection->getProductId()])) {
                $superAttributes = $buyRequest['super_attribute']
                [$bundleOption->getId()][$bundleSelection->getProductId()];
                if ($qty) {
                    foreach ($superAttributes as $code => $value) {
                        $attribute        = $this->eavConfig->getAttribute('catalog_product', $code);
                        $attributeOptions = $attribute->getSource()->getAllOptions();

                        foreach ($attributeOptions as $optionData) {
                            if ($optionData['value'] == $value) {
                                $optionHtml .=
                                    '<div style="margin-left: 28px;">' .
                                    '<i>' . $attribute->getStoreLabel() . ': ' . $optionData['label'] . '</i><br />' .
                                    '</div>';
                            }
                        }
                    }
                }
            } else {
                $superAttributes = $buyRequest['super_attribute'][$bundleOption->getId()];
                if ($qty) {
                    foreach ($superAttributes as $code => $value) {
                        $attribute        = $this->eavConfig->getAttribute('catalog_product', $code);
                        $attributeOptions = $attribute->getSource()->getAllOptions();

                        foreach ($attributeOptions as $optionData) {
                            if ($optionData['value'] == $value) {
                                $optionHtml .=
                                    '<div style="margin-left: 28px;">' .
                                    '<i>' . $attribute->getStoreLabel() . ': ' . $optionData['label'] . '</i><br />' .
                                    '</div>';
                            }
                        }
                    }
                }
            }
        }

        $coHtml = $this->getCustomOptionHtml($bundleOption, $bundleSelection, $buyRequest, $this->productModel, $item);

        return '<div class="product-item-cart-info" style="margin: 0 5px;">' .
            '<div style="margin:2px 0 0 0;">' . $qty . ' x ' . $this->escaper->escapeHtml($bundleSelection->getName())
            . '</div>' .
            '<div class-"cart-details bundle-cart-details">'
            . $optionHtml . $coHtml . '</div></div>';
    }

    /**
     * @param               $bundleOption
     * @param ItemInterface $item
     *
     * @return array
     */
    private function buildOptionData($bundleOption, ItemInterface $item)
    {

        if ($bundleOption->getSelections()) {
            $bundleOptionData = ['label' => $bundleOption->getTitle(), 'value' => []];

            $bundleSelections = $bundleOption->getSelections();
       
            foreach ($bundleSelections as $bundleSelection) {
            
            
            $leadtime = $bundleSelection->getLeadTime();

                             
            $back_order_message = '';
        $obj = \Magento\Framework\App\ObjectManager::getInstance();  
$stockRegistry = $obj->get('Magento\CatalogInventory\Api\StockRegistryInterface');
$stockitem = $stockRegistry->getStockItem($bundleSelection->getId(),$bundleSelection->getStore()->getWebsiteId());
$backorder_status = $stockitem->getBackorders();
$in_stock_status = $stockitem->getIsInStock();
$qty_in_stock = $stockitem->getQty();
    $backorder = 'no';
       
       if($backorder_status == 2 && $in_stock_status == 1 && $qty_in_stock < 1){
        $backorder = 'yes';
        $back_order_message = '<div style="color:#db2727"> - OUT OF STOCK</div>';
       }
                
            
            
                $bundleOptionData['value'][] = $this->buildSelectionData($bundleOption, $bundleSelection, $item).$back_order_message;
                $bundleOptionData['has_html'] = true;
                    $bundleOptionData['leadtime'] = $leadtime;
                       $bundleOptionData['backorder'] = $backorder;
            }

              
            return $bundleOptionData;
        }

        return [];
    }

    /**
     * Get all the set current options of the current item
     *
     * @param $bundleOption
     * @param $bundleSelection
     * @param $buyRequest
     * @param $productModel
     *
     * @return string
     */
    private function getCustomOptionHtml($bundleOption, $bundleSelection, $buyRequest, $productModel, $item = null)
    {
        $customOptionHtml = '';
        $productModel->load($bundleSelection->getId());

        if (isset($buyRequest['bundle_custom_options'])
            && isset($buyRequest['bundle_custom_options'][$bundleOption->getId()])) {
            $customOptions = $buyRequest['bundle_custom_options'][$bundleOption->getId()];

            foreach ($productModel->getOptions() as $option) {
                if (isset($customOptions[$option->getId()])) {
                    if ($option->getType() == 'date'
                        || $option->getType() == 'date_time'
                        || $option->getType() == 'time'
                    ) {
                        if ($option->getType() == 'date') {
                            $valueString = $customOptions[$option->getId()]['day'] .
                                "/" . $customOptions[$option->getId()]['month'] .
                                "/" . $customOptions[$option->getId()]['year'];
                        } elseif ($option->getType() == 'date_time') {
                            $valueString = $customOptions[$option->getId()]['day'] .
                                "/" . $customOptions[$option->getId()]['month'] .
                                "/"
                                . $customOptions[$option->getId()]['year'] .
                                ", "
                                . $option[$option->getId()]['hour'] .
                                ":" .
                                $customOptions[$option->getId()]['minute'] .
                                " " .
                                strtoupper($customOptions[$option->getId()]['day_part']);
                        } elseif ($option->getType() == 'time') {
                            $valueString = $customOptions[$option->getId()]['hour'] .
                                ":" . $customOptions[$option->getId()]['minute'] .
                                " " . strtoupper($customOptions[$option->getId()]['day_part']);
                        } else {
                            $valueString = $customOptions;
                        }
                            $optionValue = $valueString;
                        if ($optionValue) {
                            $customOptionHtml .=
                                '<div style="margin-left: 28px;">' .
                                '<i>' .
                                $option->getTitle() .
                                ': ' .
                                $optionValue .
                                ' (+ ' .
                                $this->pricingHelper->currency($option->getDefaultPrice()) .
                                ')</i><br />' .
                                '</div>';
                        }
                    }
                    if (is_array($option->getValues())) {
                        foreach ($option->getValues() as $value) {
                            if (is_string($customOptions[$option->getId()])) {
                                if ($value->getOptionTypeId() == $customOptions[$option->getId()]) {
                                    $customOptionHtml .=
                                        '<div style="margin-left: 28px;">' .
                                        '<i>' .
                                        $option->getDefaultTitle() .
                                        ': ' .
                                        $value->getTitle() .
                                        ' (+ ' .
                                        $this->pricingHelper->currency($value->getDefaultPrice()) .
                                        ')</i><br />' .
                                        '</div>';
                                }
                            } else {
                                foreach ($customOptions[$option->getId()] as $coKey => $coValue) {
                                    if ($value->getOptionTypeId() == $coValue) {
                                        $customOptionHtml .=
                                            '<div style="margin-left: 28px;">' .
                                            '<i>' .
                                            $option->getDefaultTitle() .
                                            ': ' .
                                            $value->getTitle() .
                                            ' (+ ' .
                                            $this->pricingHelper->currency($value->getDefaultPrice()) .
                                            ')</i><br />' .
                                            '</div>';
                                    }
                                }
                            }
                        }
                    } else {
                        if (is_string($customOptions[$option->getId()])) {
                            $customOptionHtml .= '<div style="margin-left: 28px;">' .
                                '<i>' . $option->getDefaultTitle() . ': ' .
                                $customOptions[$option->getId()] .
                                ' (+ ' . $this->pricingHelper->currency($option->getPrice()) . ')</i><br />' .
                                '</div>';
                        }
                    }
                }
            }
        }
        $qtyOptions = $item->getQtyOptions();
        if ($qtyOptions) {
            $customOptions = $qtyOptions[$bundleSelection->getId()]->getProduct()->getCustomOptions();
            $fileOptions = [];
            foreach ($productModel->getOptions() as $opt) {
                if ($opt->getType() == 'file') {
                    $fileOptions[$opt->getId()] = $customOptions['option_' . $opt->getId()];
                    $title = $opt->getTitle();
                    $value = $this->typeConfigurable->getDownloadUrl(
                        $customOptions['option_' . $opt->getId()]->getValue()
                    );
                    $customOptionHtml .=
                        '<div style="margin-left: 28px;">' .
                        '<i>' .
                        $title .
                        ': ' .
                        $value .
                        ' (+ ' .
                        $this->pricingHelper->currency($opt->getDefaultPrice()) .
                        ')</i><br />' .
                        '</div>';
                }
            }
        }
        return $customOptionHtml;
    }
}
