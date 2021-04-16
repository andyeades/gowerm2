<?php


namespace Elevate\CustomerGallery\Controller\Index;

class Gallerypopup extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
      	
        
    $product_id  =   $this->getRequest()->getParam('product');
            $id  =   $this->getRequest()->getParam('id');
                                                    if(!is_numeric($id)){
                                                    
                                                    echo "not a valid id";
                                                    exit;
                                                    }
        
   
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');

$connection = $resource->getConnection();

$tableName = $resource->getTableName('Your Table Name');/****** Your table name******/ 

//Select All Data Present in Table
$sql = "SELECT `main_table`.* FROM `elevate_customergallery_items` AS `main_table` WHERE items_id = $id";
      
$results = $connection->fetchAll($sql); /****** Return Array with values******/

              $product =   $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);                   
 
        $output = '

        
         <div class="row">';

 foreach($results as $socialpost){

    $output .= '<div class="col-7" style="
    padding-right: 0;
">';
 if(!empty($socialpost['image'])){ 
    
    
    $output .= '<div id="swipeContainer" class="swipeContainer row">';
    
    $output .= '<div class="scol-sm-3 ">
    <img src="/media'.$socialpost['image'].'">
    </div>';
         
}
 if(!empty($socialpost['additional_image_2'])){ 
  
      $output .= '<div class="scol-sm-3 ">
    <img src="/media'.$socialpost['additional_image_2'].'">
    </div>';
  
}
 if(!empty($socialpost['additional_image_3'])){ 
   
      $output .= '<div class="scol-sm-3 ">
    <img src="/media'.$socialpost['additional_image_3'].'">
    </div>';   
}
 if(!empty($socialpost['additional_image_4'])){ 
   
      $output .= '<div class="scol-sm-3 ">
    <img src="/media'.$socialpost['additional_image_4'].'">
    </div>';
}
 if(!empty($socialpost['additional_image_5'])){ 
  
      $output .= '<div class="scol-sm-3 ">
    <img src="/media'.$socialpost['additional_image_5'].'">
    </div>';  
}
    $output .= '</div>'; 
}
  $output .= '</div>';
      $output .= '<div class="col-5" style="
    padding-left: 0;
">';     
       $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$imageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();

$output .= '<div id="inline_prod" style="
    text-align: center;
    padding: 27px;
"><div><h3 style="
    margin-bottom: 24px;
">Shop the style</h3></div><div><img src="'.$imageUrl.'" style="
    width: 194px !important;
    border-radius: 10px;
"></div><div style="
    width: 194px !important;
    text-align: center;
    margin: 0 auto;
    margin-top: 10px;
    margin-bottom: 10px;
">'.$product->getName().'</div><div>&pound;'.$product->getFinalPrice().'</div><a target="_blank" href="'.$product->getProductUrl().'"><button class="action" title="Subscribe" type="submit" style="
    font-weight: 700;
    padding: 0 2rem;
    font-size: 0.875rem;
    line-height: 2.875rem;
    border-radius: 0;
    border-radius: 3px;
    text-transform: uppercase;
    width: 100%;
    color: #fff;
    background-color: #f0386c;
    border-color: #f0386c;
    width: 194px !important;
    margin-top: 20px;
">Shop Now</button></a></div>';



$output .= '</div>';

        $output .= '</div>';
       
          $output .= '</div>';
                 $output .= ' <script>            
  ELEVATE.CustomerGallery.runSlick();
                                 
</script>      ';
            $response['html'] = $output;
        echo json_encode($response);
  exit;
   
}
}
