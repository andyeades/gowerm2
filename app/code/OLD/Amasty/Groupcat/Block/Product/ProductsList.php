<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Amasty\Groupcat\Block\Product;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
/**
 * Catalog Products List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    
    protected function _construct()
    {
        parent::_construct();
        $this->addData(array('cache_lifetime' => null));
    }
    
    
    public function getProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
     
        $data = parent::getProductCollection()->load();
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_helperRule  =  $objectManager->get("Amasty\Groupcat\Helper\Data");

        $skuProduct = $_helperRule->getProductConditionsRule();
        
        if (!empty($skuProduct)){
            $arr_sku = explode(",",$skuProduct);
       
            foreach ($arr_sku as $key => $val){
                $arr_sku[$key] = trim($val);
            }
            
            $data->addAttributeToFilter('sku',$arr_sku);
         
            $arr_id = [];
           
            if (count($data->getData()) != 0 ){
                foreach ($data->getData() as $val){
                    $arr_id[] = $val['entity_id'];
                }
            }
            $productCollectionFactory = $objectManager->get("Magento\Catalog\Model\ResourceModel\Product\CollectionFactory");
            $result = $productCollectionFactory
            ->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $arr_id]);
            
            return $result;
            
        }
        else {
            return [];
        }
       
     
        
    }
}