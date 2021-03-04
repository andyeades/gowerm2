<?php
namespace Elevate\CustomerGallery\Controller\Index;


use Elevate\CustomerGallery\Model\Items;


class Postkids extends \Magento\Framework\App\Action\Action
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


        $draw_school =  $this->getRequest()->getParam('draw_school');
        $draw_age =  $this->getRequest()->getParam('draw_age');
        $draw_region =  $this->getRequest()->getParam('draw_region');
        $winner =  $this->getRequest()->getParam('winner');
        $binds = [];
        $query_filters_imp = [];

        if(!empty($winner) && ($winner == 1 || $winner == 2)){
            $query_filters_imp[] = "winner = :winner";
            $binds[':winner'] = $winner;
        }
        if(!empty($draw_school)){
            $query_filters_imp[] = "draw_school = :draw_school";
            $binds[':draw_school'] = $draw_school;
        }

        if(!empty($draw_age)){
            $query_filters_imp[] = "draw_age = :draw_age";
            $binds[':draw_age'] = $draw_age;
        }

        if(!empty($draw_region)){
            $query_filters_imp[] = "draw_region = :draw_region";
            $binds[':draw_region'] = $draw_region;
        }
        $query_filters = implode(' AND ', $query_filters_imp);
        if(!empty($query_filters)){

            $query_filters = ' AND '.$query_filters;
        }
        if($this->getRequest()->getParam('p'))
        {
            $curr_page  =   $this->getRequest()->getParam('p');
        }
        //Calculate Offset
        $offset     =   ($curr_page - 1) * $limit;

        //   $collection = Mage::getModel('customergallery/items')->getCollection()
        //                ->addFieldToFilter('status', 1);

        //    $collection->getSelect()->limit($limit,$offset);


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $readConnection = $resource->getConnection();


        //AND (e.entity_id !='1284061'
        $query = "
     SELECT  `items`.* 
    
    FROM `elevate_customergallery_items` AS `items`
 WHERE items.status = 1 AND location = 'draw'
    $query_filters
    ORDER BY created_at DESC
 LIMIT $offset, $limit 
 
    ";

        if(isset($_GET['all']) && $_GET['all'] == 1){
            $query = "
     SELECT  `items`.* 
    
    FROM `elevate_customergallery_items` AS `items`
 WHERE items.status = 1 AND location = 'draw'
    $query_filters
    ORDER BY created_at DESC

 
    ";}
        $response['more'] = 1;

        /**
         * Execute the query and store the results in $results
         */
        //  echo $query;
        // print_r($binds);
        $results = $readConnection->query($query, $binds);



        $template_count = 0;
        $htmlOutput = '<div class="mosaic">';
        $i = 0;
        $has_res = false;
        foreach($results as $socialpost) {
            $has_res = true;
            $template_count++;
            $i++;

            if ($socialpost['post_type'] == 1) {
                $uNameSymbol = '@';
            }
            if ($socialpost['post_type'] == 2) {
                $uNameSymbol = '@';
            }



            $htmlOutput .= '<div class=" image-outer evlightbox" data-body-type="ajax" data-json="true" data-body="/customer-gallery/index/drawpopup/id/'.$socialpost['product_id'].'/itemid/'.$socialpost['items_id'].'">

	<img data-id="'.$socialpost['items_id'].'" src="https://www.happybeds.co.uk/media'.$socialpost['image'].'" /><div class="circ_out">
			<i class="fa fa-plus-circle" aria-hidden="true"></i><div style="float:right;"><span style="margin-top: 6px;float: left;">'.$socialpost['name'].'</span>';

            if(is_numeric($socialpost['draw_age'])){
                $htmlOutput .= '<span style="
    margin-top: 6px;
    float: left;
    margin-left: 6px;
"> - Age '.$socialpost['draw_age'].'</span>';
            }
            $htmlOutput .= '</div></div>
<div class="overlay">
                           
              </div>
		</div> ';
        }


        if($i < 12){
            $response['more'] = 0;
        }
        $htmlOutput .= '</div>
        
<script>

</script>
        <script>ELEVATE.Lightbox.attachEventHandlers();</script>';


        if(!$has_res){
            $htmlOutput = '<div style="    text-align: center;
    color: floralwhite;
    font-size: 20px;
    margin-top: 20px;">There are no entries matching your selected filters.</div>';
        }
        $html = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($htmlOutput));
        $response['html'] = $html;

        echo json_encode($response);
        exit;

      //  $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));


	}
    
    public function unique_id($l = 8) {
        return substr(md5(uniqid(mt_rand(), true)), 0, $l);
    }
    
}