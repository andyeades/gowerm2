<?php
namespace Elevate\Promotions\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
        public function getConfigByStore($config_path, $store_id)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store_id
        );
    } 
    
    
    
 public function getNotLoggedInCustomer(){
    
    
         $not_logged_in_email = $this->getConfig('mconnector/custom_settings/not_logged_in_email');
       
       
         if(!empty($not_logged_in_email)){
          $is_nav_customer = true;
              $currentCustomer = $this->customerFactory->create()->loadByEmail($not_logged_in_email); 
          }
          
         $coupon_customer = 'BLA02@posturite.co.uk'; 
          
         if(!empty($not_logged_in_email)){
          $is_nav_customer = true;
              $currentCustomer = $this->customerFactory->create()->loadByEmail($not_logged_in_email); 
          }       
    

          if(!$currentCustomer || !$currentCustomer->getId()){
  
          
          $om = \Magento\Framework\App\ObjectManager::getInstance();
          $sessionFactory = $om->get('Magento\Customer\Model\SessionFactory');
  
          $customerSession = $sessionFactory->create();
          $currentCustomer = $customerSession->getCustomer();
                           
   
              $is_contact = (bool) $currentCustomer->getIsContact();   
              if($is_contact){
                  $parent_customer_id = $currentCustomer->getNavContactCustomerId();
                  if(!empty($parent_customer_id)){
                      $parent_customer =  $currentCustomer
                          ->getCollection()
                          ->addAttributeToSelect(['*'])
                          ->addAttributeToFilter('navision_customer_id', $parent_customer_id)
                          ->getFirstItem();
                      if($parent_customer && $parent_customer->getId()){
                          $currentCustomer = $parent_customer;
                      }
                  }
  
              }
              
          }
          
          return $currentCustomer;
          
    }
                        
}
