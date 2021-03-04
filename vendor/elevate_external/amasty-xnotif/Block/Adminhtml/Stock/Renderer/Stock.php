<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Xnotif
 */
namespace Amasty\Xnotif\Block\Adminhtml\Stock\Renderer;

use \Magento\Framework\DataObject;

/**
 * Class Website
 */
class Stock extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    
  private $stockItem;
 
    public function __construct(
        \Magento\Backend\Block\Context $context,
         \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        array $data = []
    ) {
        parent::__construct($context, $data);
           $this->stockItem = $stockItem;
    }
  

/**
 * Render product qty field
 *
 * @param \Magento\Framework\DataObject $row
 * @return string
 */
public function render(DataObject $row)
{
            $id = $row->getData('entity_id');
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

       $productQty = 0;
                  
         /*
    $stockItem = $objectManager->create('\Magento\CatalogInventory\Model\Stock\StockItemRepository');
if($id){
    $productQty = $stockItem->get($id)->getQty();
   }
   */
       
           return $this->stockItem->getStockQty($id, $row->getStore()->getWebsiteId());

}

}
