<?php


namespace Elevate\LandingPages\Observer;



 
 class CategorySaveAfter implements \Magento\Framework\Event\ObserverInterface
{

      protected $_landingPageFaqFactory;
      protected $_landingPageFaqCollectionFactory;
    public function __construct(
        \Elevate\LandingPages\Model\LandingPageFaqFactory $landingPageFaqFactory,
        \Elevate\LandingPages\Model\ResourceModel\LandingPageFaq\CollectionFactory $landingPageFaqCollectionFactory
    ) {

        $this->_landingPageFaqFactory = $landingPageFaqFactory;
       $this->_landingPageFaqCollectionFactory = $landingPageFaqCollectionFactory;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Category $category */
        $category = $observer->getEvent()->getData('category');

    $category_id = $category->getId();
           
     $faq_data = $category->getData('elevate_landingpages_schema_faq');   
  
                  
         
     if(isset($faq_data)){

      
        $landingloadCollection = $this->_landingPageFaqCollectionFactory->create()->load('category_id', $category_id);  
        foreach($landingloadCollection as $landingload){  
      
      if($landingload->getData('category_id') == $category_id){
      
        $landingload->delete();
        }
        }


      
        foreach ($faq_data as $faq) {
        
          
            $question = $faq['question'];
            $answer =  $faq['answer'];
                $position =  $faq['position'];
           
            


                //new record
                $landingload = $this->_landingPageFaqFactory->create();

              
                $landata['question'] = $question;
                $landata['answer'] = $answer;
                $landata['category_id'] = $category_id;
                 $landata['position'] = $position;
                //  $landata = array_filter($landata, function($value) {return $value !== ''; });

                //   print_r($landata);
                $landingload->setData($landata);
                try {
                    //   echo "TRY";
                    $landingload->save();

                } catch(Exception $e) {
                    print_r($e->getMessage());
                    //exit;
                }

    
       
            //what is left in $landing_map we need to delete

        }
      
        }

  
    }
}
