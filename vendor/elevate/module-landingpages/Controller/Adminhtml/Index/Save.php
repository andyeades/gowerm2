<?php

namespace Elevate\LandingPages\Controller\Adminhtml\Index;

class Save extends \Magento\Backend\App\Action {

    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;
    protected $contactFactory;
    protected $landingAttributeFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context                     $context,
        \Magento\Framework\View\Result\PageFactory              $resultPageFactory,
        \Elevate\LandingPages\Model\LandingPageFactory          $contactFactory,
        \Elevate\LandingPages\Model\LandingPageAttributeFactory $landingAttributeFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->contactFactory = $contactFactory;
        $this->landingAttributeFactory = $landingAttributeFactory;

        parent::__construct($context);
    }

    public function execute() {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $id = $data['landingpage_id'];

        //convert category array to string - otherwise Notice Error - Array to String conversion on save
        if (isset($data['category_ids'])) {
            if (is_array($data['category_ids'])) {
                $data['category_ids'] = implode(',', $data['category_ids']);
            }
        }

        if (isset($data['attribute_options_select'])) {
            $landingAttributes = $data['attribute_options_select'];
        }
        $landingFactory = $this->landingAttributeFactory;

        $landing_map = [];
        //what attributes already exist?
        //does it already exist?
        $landing_current = $landingFactory->create()->getCollection()->addFieldToFilter('landingpage_landingpage_id', $id)->load();

        foreach ($landing_current as $page_current) {

            $landing_map[$page_current->getData('landingpage_attributes_id')] = $page_current->getData('landingpage_attributes_id');

        }
        

        
        if (isset($landingAttributes)) {
            foreach ($landingAttributes as $attribute) {
                $attribute_id = $attribute['attribute_id'];
                $option_id = $attribute['option_id'];
                $landata = [];
                $landingpage_attributes_id = $attribute['landingpage_attributes_id'];

                if (empty($landingpage_attributes_id)) {

                    //new record
                    $landingload = $this->landingAttributeFactory->create();

                    $landata['landingpage_landingpage_id'] = $id;
                    $landata['attribute_id'] = $attribute_id;
                    $landata['option_id'] = $option_id;

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

                    continue;
                }

                //does it already exist?
                $landing = $landingFactory->create()->getCollection()->addFieldToFilter('landingpage_landingpage_id', $id)->addFieldToFilter('landingpage_attributes_id', $landingpage_attributes_id)->addFieldToFilter('attribute_id', $attribute_id)->load();

                foreach ($landing as $lan) {


                    $landid = $lan->getData('landingpage_attributes_id');
                    if (is_numeric($landid)) {
                        // remove all that dont need to be deleted
                        unset($landing_map[$landid]);
                    }

                    $landingload = $this->landingAttributeFactory->create()->load($landid);

                    $landata = $landingload->getData();
                    $landata['attribute_id'] = $attribute_id;
                    $landata['option_id'] = $option_id;

                    $landata = array_filter($landata, function ($value) { return $value !== ''; });

                    $landingload->setData($landata);
                    $landingload->save();

                }

                //what is left in $landing_map we need to delete

            }
        }
        //   exit;
        foreach ($landing_map as $key => $val) {
            $landing = $landingFactory->create()//->addFieldToFilter('landingpage_attributes_id', $key)
                                      ->load($key);
            print_r($landing->getData());
            $landing->delete();
        }

        //exit;
        if ($data) {


            //save landing page faq

     













/*SORT LANDING PAGES*/

   $faq_landing_map = [];
        //what attributes already exist?
        //does it already exist?
  

       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $landingPageFaqFactory = $objectManager->get('\Elevate\LandingPages\Model\LandingPageFaqFactory');
            $landingPageFaqCollectionFactory = $objectManager->get('\Elevate\LandingPages\Model\ResourceModel\LandingPageFaq\CollectionFactory');
            
            
            $faqFactory = $objectManager->get('\Elevate\LandingPages\Model\LandingPageFaqFactory');
            
   $landingloadCollection = $faqFactory->create()->getCollection()->addFieldToFilter('landingpage_id', $id)->load();


        foreach ($landingloadCollection as $page_current) {

            $faq_landing_map[$page_current->getData('landingpage_faq_id')] = $page_current->getData('landingpage_faq_id');

        }
        
            
        
  if (isset($data['elevate_landingpages_schema_faq'])) {
  

  

  
            foreach ($data['elevate_landingpages_schema_faq'] as $faq) {
           
                
                    $question = $faq['question'];
                    $answer = $faq['answer'];
                    $position = $faq['position'];

              
                
                  
                
                
                
                $landata = [];
                $landingpage_faq_id = $faq['landingpage_faq_id'];

                if (empty($landingpage_faq_id)) {

                    //new record
                 
                    $landingload = $landingPageFaqFactory->create();
                    
                    
                     $landata['question'] = $question;
                    $landata['answer'] = $answer;
                    $landata['landingpage_id'] = $id;
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

                    continue;
                }


                //does it already exist?
                $landing = $landingPageFaqFactory->create()->getCollection()->addFieldToFilter('landingpage_id', $id)->addFieldToFilter('landingpage_faq_id', $landingpage_faq_id)->load();

                foreach ($landing as $lan) {


                    $landid = $lan->getData('landingpage_faq_id');
                    if (is_numeric($landid)) {
                        // remove all that dont need to be deleted
                        unset($faq_landing_map[$landid]);
                    }

                    $landingload = $landingPageFaqFactory->create()->load($landid);

                    $landata = $landingload->getData();
                    $landata['attribute_id'] = $attribute_id;
                    $landata['option_id'] = $option_id;

                    $landata = array_filter($landata, function ($value) { return $value !== ''; });

                    $landingload->setData($landata);
                    $landingload->save();

                }

                //what is left in $landing_map we need to delete

            }
        }
        
  
        //   exit;
        foreach ($faq_landing_map as $key => $val) {
            $landing = $landingPageFaqFactory->create()//->addFieldToFilter('landingpage_attributes_id', $key)
                                      ->load($key);
            print_r($landing->getData());
            $landing->delete();
        }
        
/*End Sort Landing Pages*/




/*

            if (isset($data['elevate_landingpages_schema_faq'])) {



            foreach ($landingloadCollection as $landingload) {

                if ($landingload->getData('landingpage_id') == $id) {

                 //   $landingload->delete();

                }
            }

                $faq_data = $data['elevate_landingpages_schema_faq'];

                foreach ($faq_data as $faq) {


                    $question = $faq['question'];
                    $answer = $faq['answer'];
                    $position = $faq['position'];

                    //new record
                    $landingload = $landingPageFaqFactory->create();

                    $landata['question'] = $question;
                    $landata['answer'] = $answer;
                    $landata['landingpage_id'] = $id;
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
*/
            ///end faq

            try {


                $contact = $this->contactFactory->create()->load($id);

                // THis causes problems when commented out and uncommented!!!!
                $data = array_filter($data, function ($value) { return $value !== ''; });

                $contact->setData($data);

                $contact->save();

                $this->messageManager->addSuccess(__('Successfully saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

            } catch(\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);

                return $resultRedirect->setPath('*/*/edit', ['id' => $contact->getId()]);
            }
        }

        $this->_eventManager->dispatch('elevate_landingpages_save_after', ['landingpage' => $contact]);

        //check for `back` parameter
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', [
                                                          'id'       => $contact->getId(),
                                                          '_current' => true
                                                      ]);
        }

        return $resultRedirect->setPath('*/*/');

    }
}
