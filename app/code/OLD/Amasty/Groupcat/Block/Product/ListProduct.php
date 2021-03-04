<?php

/**
 * Webkul_DailyDeals ListProduct collection block.
 * @category  Webkul
 * @package   Webkul_DailyDeals
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Amasty\Groupcat\Block\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Reports\Model\ResourceModel\Product as ReportsProducts;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers as SalesReportFactory;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productFactory;

    /**
     * @var Magento\Reports\Model\ResourceModel\Product
     */
    private $reportproductsFactory;

    /**
     * @var Magento\Sales\Model\ResourceModel\Report\Bestsellers
     */
    private $salesReportFactory;

    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver     $layerResolver
     * @param CategoryRepositoryInterface               $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data        $urlHelper
     * @param CollectionFactory                         $productFactory
     * @param ReportsProducts\CollectionFactory         $reportproductsFactory,
     * @param SalesReportFactory\CollectionFactory      $salesReportFactory
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        CollectionFactory $productFactory,
        ReportsProducts\CollectionFactory $reportproductsFactory,
        SalesReportFactory\CollectionFactory $salesReportFactory
    ) {
        $this->productFactory = $productFactory;
        $this->reportproductsFactory = $reportproductsFactory;
        $this->salesReportFactory = $salesReportFactory;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper
        );
        $this->today = $this->_localeDate->convertConfigTimeToUtc($this->_localeDate->date());
    }
    
    /**
     * @return Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    protected function _getProductCollection()
    {
       
        $data = parent::_getProductCollection()->load();
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
           
            $data = $this->reportproductsFactory
            ->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $arr_id]);
            $this->_productlists = $data;
            $this->_productlists->getSize();
            return $this->_productlists;
            
        }
        else {
            return array();
        }
        
        
    }

   
}
