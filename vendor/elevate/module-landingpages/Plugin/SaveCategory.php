<?php    
namespace Elevate\LandingPages\Plugin;

class SaveCategory 
{


    protected $_landingPageFaqFactory;

    public function __construct(
        \Elevate\LandingPages\Model\LandingPageFaqFactory $landingPageFaqFactory
    ) {

        $this->_landingPageFaqFactory = $landingPageFaqFactory;

    }
    
 public function afterSave(\Magento\Catalog\Model\CategoryRepository $subject, $category)
    {
     
     /*  
        $category_id = $category->getId();
           
     $faq_data = $category->getData('elevate_landingpages_schema_faq');   
  

         
     if(isset($faq_data)){

        foreach ($faq_data as $faq) {
        
        $landingload = $this->_landingPageFaqFactory->create()->load($faq['landingpage_faq_id']);
        
        $landingload->delete();
     }
        foreach ($faq_data as $faq) {
        
            $landingpage_faq_id = $faq['landingpage_faq_id'];
            $question = $faq['question'];
            $answer =  $faq['answer'];
            
           
            

            if (empty($landingpage_faq_id)) {

                //new record
                $landingload = $this->_landingPageFaqFactory->create();

                $landata['landingpage_faq_id'] = $landingpage_faq_id;
                $landata['question'] = $question;
                $landata['answer'] = $answer;
                $landata['category_id'] = $category_id;
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

    
            }

       
            //what is left in $landing_map we need to delete

        }
      
        }
           */ 
    }
}