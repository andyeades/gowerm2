<?php


namespace Elevate\LandingPages\Model\LandingPage;

use Elevate\LandingPages\Model\ResourceModel\LandingPage\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {

            //need to explode the category ids for the data to load properly in the form
            //otherwise its just broken widget
            $model['category_ids'] = explode(',', $model['category_ids']);
            $this->loadedData[$model->getId()] = $model->getData();
        }
        $data = $this->dataPersistor->get('elevate_landingpages');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('elevate_landingpages');
        }


        $this->loadAttributeData();
                $this->loadFaqData();
        return $this->loadedData;
    }
    function loadAttributeData(){


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $productCollection = $objectManager->create('Elevate\LandingPages\Model\ResourceModel\LandingPageAttribute\CollectionFactory');


        $items = $this->collection->getItems();

        foreach($items as $contact)
        {
            $this->_loadedData[$contact->getId()] = $contact->getData();



            $collection = $productCollection->create()
                // ->addAttributeToSelect('*')
                                            ->addFieldToFilter('landingpage_landingpage_id', $contact->getId())
                                            ->load();

            foreach ($collection as $product){




                $this->loadedData[$contact->getId()]['attribute_options_select'][] = array(
                    'landingpage_attributes_id' => $product->getData('landingpage_attributes_id'),
                    'attribute_id' => $product->getAttributeId(),
                    'original_option_id' => $product->getOptionId(),
                    'option_id' => $product->getOptionId()
                );
                //  echo 'Name  =  '.$product->getAttributeId().'<br>';
            }

            //  exit;

        }

    }
    
    
        function loadFaqData(){


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $productCollection = $objectManager->create('\Elevate\LandingPages\Model\ResourceModel\LandingPageFaq\CollectionFactory');


        $items = $this->collection->getItems();

        foreach($items as $contact)
        {
            $this->_loadedData[$contact->getId()] = $contact->getData();


         
            $collection = $productCollection->create()
            //  ->addAttributeToSelect('*')
                                            ->addFieldToFilter('landingpage_id', $contact->getId())
                                            ->setOrder('position','ASC')
                                            ->load();
                                                                                                                                                                                                    


            foreach ($collection as $product){
            
      
               $this->loadedData[$contact->getId()]['elevate_landingpages_schema_faq'][] = array(
                    'landingpage_faq_id' => $product->getData('landingpage_faq_id'),
                    'question' => $product->getQuestion(),
                    'answer' => $product->getAnswer(),
                    'position' => $product->getPosition()
                );
                //  echo 'Name  =  '.$product->getAttributeId().'<br>';
            }


            //  exit;

        }

    }
    
}
