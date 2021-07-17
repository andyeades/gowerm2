<?php
namespace Elevate\LandingPages\Model\Category;

class DataProvider 
{
    protected $collection;
    protected $collectionFactory;
    protected $loadedData;


    public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {
    
    
 
        if($result){
            $category_id = key($result);



        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $productCollection = $objectManager->create('Elevate\LandingPages\Model\ResourceModel\LandingPageFaq\CollectionFactory');


     //   $items = $this->collection->getItems();

   
         //   $this->_loadedData[$contact->getId()] = $contact->getData();


             
            $collection = $productCollection->create()
            //  ->addAttributeToSelect('*')
                                            ->addFieldToFilter('category_id', $category_id)
                                            ->setOrder('position','ASC')
                                            ->load();
                                                                                                                                                                                                    


            foreach ($collection as $product){
            
      
               $result[$category_id]['elevate_landingpages_schema_faq'][] = array(
                    'landingpage_faq_id' => $product->getData('landingpage_faq_id'),
                    'question' => $product->getQuestion(),
                    'answer' => $product->getAnswer(),
                    'position' => $product->getPosition()
                );
                //  echo 'Name  =  '.$product->getAttributeId().'<br>';
            }

            //  exit;

      
    }
            
         return $result;
}
}