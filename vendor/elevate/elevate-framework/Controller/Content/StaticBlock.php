<?php

namespace Elevate\Framework\Controller\Content;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class StaticBlock extends \Magento\Framework\App\Action\Action
{
protected $blockFactory;
    protected $blockResource;

    /**
     * @var Session
     */
    private $session;
    /**
     * @var StockItemRepository
     */

    /**
     * @var Data
     */
    protected $_jsonHelper;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

     protected $storeManager;


    /**
     * Index constructor.
     * @param Context $context
     * @param Data $jsonHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $session,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Cms\Model\ResourceModel\Block $blockResource,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->_logger = $logger;
        $this->session = $session;
        $this->blockFactory = $blockFactory;
        $this->blockResource = $blockResource;
        $this->storeManager = $storeManager;

    }
        public function getStaticBlock($identifier){
        try {
            $block = $this->blockFactory->create();
            $block->setStoreId($this->storeManager->getStore()->getId());
            $this->blockResource->load($block, $identifier);
            if (!$block->getId()) {
             return false;
                throw new NoSuchEntityException(__('CMS Block with identifier "%1" does not exist.', $identifier));
            }
            return $block;
        } catch(\Exception $e){
            //print_r($e->getMessage());
        }
        return false;
    }

    public function getCmsBlockContent($identifier){
            $array = [];
        $staticBlock = $this->getStaticBlock($identifier);
               //&& $staticBlock->isActive()        
        if($staticBlock){
             $array['content'] = $staticBlock->getContent();
             $array['title']  = $staticBlock->getTitle();
             return $array;
        }

        return false;
    }
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
                

        $customerData = [];
        $responseData = [];
        $isLoggedIn = false;

      
        $identifier = $this->getRequest()->getParam("identifier");
       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$filterProvider = $objectManager->create('\Magento\Cms\Model\Template\FilterProvider');
$storeManager = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
 
                                    $content = $this->getCmsBlockContent($identifier);
                                    if(!$content){
                                     echo __('Static block content not found');
                                     exit;
                                    }
                  
preg_match_all('!\d+!', $identifier, $matches);

                              
        if(!isset($matches[0])){
          echo __('Static block content not found');
           exit;
        }
      
      
      $calendar_day = $matches[0];  
  $dateTime = new \DateTime();
/**
 * You can get the string by using format
 */
$current_date = $dateTime->format('d');
if($current_date > 25){
$current_date = 1;
}      
         
if(isset($_GET['dayoverride'])){

$current_date =  $_GET['dayoverride'];
}
  

    $expire = $this->getRequest()->getParam("expire");

    if($expire == 'yes' && $current_date > $calendar_day){
     echo __('This Deal has expired');
      exit;
    }
      
else if($calendar_day >= $current_date){
 



        $html = $filterProvider->getBlockFilter()
                            ->setStoreId($storeManager->getStore()->getId())
                            ->filter($content['content']);
                    }
                    else if($calendar_day < $current_date){
                    
                      echo __('This deal will be open soon');
      exit;
                    
                    
                    }
                    
                    
                    
                    
        $responseData = [
            'errors' => false,
            'html' => $html,
            'title' => $content['title']
        ];
        echo json_encode($responseData);

         exit;
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->_jsonHelper->jsonEncode($response)
        );
    }


}