<?php
namespace Elevate\LandingPages\Model\LandingPageAttribute;

use Elevate\LandingPages\Model\ResourceModel\LandingPageAttribute\CollectionFactory;
use Elevate\LandingPages\Model\LandingPageAttribute;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ){
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }

 
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$productCollection = $objectManager->create('Elevate\LandingPages\Model\ResourceModel\LandingPageAttribute\CollectionFactory');


        $items = $this->collection->getItems();

        foreach($items as $contact)
        {
            $this->_loadedData[$contact->getId()] = $contact->getData();
            
            

$collection = $productCollection->create()
            ->addAttributeToSelect('*')
            //->addFieldToFilter('landingpage_landingpage_id', '1')
            ->load();

foreach ($collection as $product){
     echo 'Name  =  '.$product->getName().'<br>';
}              
            
          exit;  
            
        }

        return $this->_loadedData;
    }
        
}
