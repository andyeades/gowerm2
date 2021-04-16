<?php
namespace Elevate\CustomerGallery\Controller\Index;


use Elevate\CustomerGallery\Model\Items;


class Posts extends \Magento\Framework\App\Action\Action 
{
	protected $_pageFactory;
    
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory
    ){
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
        
        $limit      =   12;
        $curr_page  =   1;
        $icon_overlay = '<i class="fa fa-plus-circle" aria-hidden="true"></i>'; //ovelay an icon on the image
        $icon_overlay = '';      
        $lightbox_endpoint = '/weltpixel_quickview/catalog_product/view/id/';//use this for welt pixel add to cart popup 
        $lightbox_endpoint = '/customer-gallery/index/gallerypopup/id/';
        
        
        if($this->getRequest()->getParam('p'))
        {
            $curr_page  =   $this->getRequest()->getParam('p');
        }
        //Calculate Offset  
        $offset     =   ($curr_page - 1) * $limit;
        
        
        
   
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');

$connection = $resource->getConnection();

$tableName = $resource->getTableName('Your Table Name');/****** Your table name******/ 

//Select All Data Present in Table
$sql = "SELECT `main_table`.* FROM `elevate_customergallery_items` AS `main_table` WHERE (`status` LIKE 1) AND (`location` = 'customergallery') LIMIT $limit OFFSET $offset";
      
$results = $connection->fetchAll($sql); /****** Return Array with values******/
                           
        $template_count = 0;
        $htmlOutput = '<div class="row">';
        $i = 0;   
 foreach($results as $socialpost){
    $template_count++;
      $i++;
         $uNameSymbol = '';    
        
        if($socialpost['post_type'] == 1){
            $uNameSymbol = '@';
        }
       if($socialpost['post_type'] == 2){
            $uNameSymbol = '@';
        }
    

    if($template_count == 1 ){ 
    
      $htmlOutput .= '
      <div class="col-md-6 col-xs-6" style="margin-top:7px;">
      <div class="row">
        <div class="col-md-4">
          <div class="row" style="padding-bottom: 7px;">
            <div class="col-md-12 image-outer">';        
    }     
    if($template_count == 2){
      $htmlOutput .= '<div class="row" style="padding-top: 7px;padding-bottom: 7px;">
            <div class="col-md-12 image-outer">';        
    }
    if($template_count == 3){
      $htmlOutput .= '<div class="col-md-8 image-outer">';        
    }             
    if($template_count == 4 ){
      $htmlOutput .= '<div class="row" style="padding-top: 7px;">
        <div class="col-md-4 image-outer"> ';        
    }   
  
    
 

      if($template_count == 5){
      $htmlOutput .= '<div class="col-md-4 image-outer">';        
    }         
    if($template_count == 6){
    
      $htmlOutput .= '
           
      <div class="col-md-4 image-outer">';        
    }
        if( $template_count == 7){
      $htmlOutput .= '
         <div class="col-md-6 col-xs-6"" style="margin-top:7px;">
      <div class="row" style="padding-bottom: 7px;">
        <div class="col-md-4 image-outer"> ';        
    }   
    
        if($template_count == 8){
      $htmlOutput .= '<div class="col-md-4 image-outer">';        
    }  
    if($template_count == 9){
      $htmlOutput .= '<div class="col-md-4 image-outer">';        
    }  
    if($template_count == 10){
      $htmlOutput .= '<div class="row" style="padding-top: 7px;">
        <div class="col-md-8 image-outer"> ';        
    }  
    if($template_count == 11){
      $htmlOutput .= '        <div class="col-md-4">
          <div class="row" style="padding-bottom: 7px;">
            <div class="col-md-12 image-outer"> ';        
    }     
    
    if($template_count == 12){
      
      $htmlOutput .= '
       
      <div class="row" style="padding-top: 7px;padding-bottom: 7px;">
            <div class="col-md-12 image-outer">';        
    }         

$htmlOutput .= '<div class="evlightbox" data-body="'.$lightbox_endpoint.$socialpost['items_id'].'/product/'.$socialpost['product_id'].'" data-body-type="ajax">

	<img data-id="'.$socialpost['items_id'].'" src="/media'.$socialpost['image'].'" />
		'.$icon_overlay.'
<div class="overlay">
                           <div class="user-handle-hidden">
'.$uNameSymbol.' '.$socialpost['name'].'
</div>
              </div>
		</div> ';


    if($template_count == 1 ){
      $htmlOutput .= '</div>
          </div>';     
    }
    if($template_count == 2 ){
      $htmlOutput .= '</div>
          </div></div>';     
    }
    if($template_count == 3 ){
      $htmlOutput .= '</div>
          </div>';     
    }
    if($template_count == 4 ){
      $htmlOutput .= '</div>';     
    } 
    if($template_count == 5 ){
      $htmlOutput .= '</div>';     
    } 
    if($template_count == 6){
      $htmlOutput .= '
       </div>
       
      </div></div>';     
    }
    if($template_count == 7 ){
      $htmlOutput .= '</div>';     
    } 
    if($template_count == 8 ){
      $htmlOutput .= '</div>';     
    }    

    if($template_count == 9 ){
      $htmlOutput .= '</div></div>';     
    }
    if($template_count == 10 ){
      $htmlOutput .= '</div>
        ';     
    }    
      if($template_count == 11 ){
      $htmlOutput .= '</div>
          </div>';     
    }  
    if($template_count == 12 ){
      $htmlOutput .= '            </div> </div>
          </div>
        </div>
      </div>';   
      $template_count = 0;     
    }
 
    
    
     }     
        
          if($template_count == 1){
           $htmlOutput .= "</div>";
           $htmlOutput .= "</div>";
           $htmlOutput .= "</div>";     
      }
      if($template_count == 2){
           $htmlOutput .= "</div>";
           $htmlOutput .= "</div>";
           
      } 
      
      $htmlOutput .= '</div>';
          
    
          if($i < 12){
           $response['more'] = 0;  
           }
      $htmlOutput .= '<script>ELEVATE.Lightbox.attachEventHandlers();</script>';
 


        $html = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($htmlOutput));           
    $response['html'] = $html;
//        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                echo json_encode($response);
            exit;
              
	}
    
    public function unique_id($l = 8) {
        return substr(md5(uniqid(mt_rand(), true)), 0, $l);
    }
    
}