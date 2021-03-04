<?php
namespace Elevate\LandingPages\Model\Rewrite\Layer\Filter;

use Elevate\LandingPages\Model\Rewrite\Layer\Filter as FilterModel;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{

    protected $_categoryFactory;

    protected $landingPage;


    protected $_linkParameters;

    protected $_request;

    protected $_coreRegistry;
    /**
     * @var FilterModel
     *
     */
    protected $_urlSuffix = false;
    protected $_filterModel;

    protected $_scopeConfig;
    /**
     * Construct
     * @param FilterModel $filterModel
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Theme\Block\Html\Pager $htmlPagerBlock
     * @param array $data
     *
     */
    public function __construct(
        FilterModel $filterModel,
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $coreRegistry,
        \Elevate\LandingPages\Model\LandingPage $landingPage,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,




        array $data = []
    ) {
        $this->_filterModel = $filterModel;
        $this->_categoryFactory = $categoryFactory;
        $this->_request = $request;
        $this->_scopeConfig = $scopeConfig;
        $this->_coreRegistry = $coreRegistry;
        $this->landingPage = $landingPage;
        parent::__construct($url, $htmlPagerBlock, $data);
    }
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getLevel1Category(){
        if($this->getCurrentCategory()){
            if($this->getCurrentCategory()->getParentCategories()){
                foreach ($this->getCurrentCategory()->getParentCategories() as $parent) {
                    if ($parent->getLevel() == 1) {
                        // reurns the level 1 category id;
                        return $parent->getId();
                    }
                }
            }
        }
        return null;
    }
public function getUrlSuffix(){
if($this->_urlSuffix){

    return $this->_urlSuffix;
}

    $this->_urlSuffix = $this->_scopeConfig->getValue('catalog/seo/category_url_suffix', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
return $this->_urlSuffix;
}

 public function getLinkParametersArray($query){
     //New Code



     $current_parameters = array();//Mage::app()->getRequest()->getParams();

     //does this have new parameters?
     $new_link = true;
     $attributes_array = $current_parameters;
     foreach($query AS $key=>$val){
         $this->_linkParameters[$key] = $val;


         // why
         if(isset($current_parameters[$key])){
             if($current_parameters[$key] != $val){

             }
             else{
                 $new_link = false;
                 //http://s2.happybeds.co.uk/beds/bunk-beds?choose_size=single-3-x-6-3&bunk_bed_type=triple-sleeper - this hits this
           
             }
             /// end why

         }
         else{

         }
     }



}
    public function isSelected() {
        //  $selected = $this->_request->getParam($this->getFilter()->getRequestVar());

        $values = $this->_request->getParam($this->getFilter()->getRequestVar());
        $value = $this->getValue();
        if (!is_null($values) && in_array($value, explode(',', $values))) {
            return true;
        }

        return false;

        // if($selected == $this->getValue()){
        //     return true;
        // }else{
        //     return false;
        // }

    }

    /**
     * Get url for "clear" link
     *
     * @return false|string
     */
    public function getClearLinkUrl()
    {



        $clearLinkText = $this->getFilter()->getClearLinkText();
        if (!$clearLinkText) {
            return false;
        }

        $currentLandingAttributes = $this->_coreRegistry->registry('elevate_landingpage_attributes');
        $contactModel = $this->landingPage;

        $category = false;
        //  \Magento\Framework\Profiler::start('loadByAttributesCalc');
        if(is_array($currentLandingAttributes)){

            // unset one


            $currentLandingAttributes = array_flip($currentLandingAttributes);
            //unset($currentLandingAttributes[$itemValue]);
            $currentLandingAttributes = array_flip($currentLandingAttributes);


            $landingPage = $contactModel->loadByAttributesCalc($currentLandingAttributes, $category, false);
            if(isset($landingPage['landingpage_id'])) {

                //    if(is_numeric($landingPage['landingpage_id']) && count($count_vals) < 2) {
                $url = $landingPage['url_key'];
                $url_arr = explode('/', $url);

            }
            $url = "/".$url."?".$url_params_split[1];
            return $url;
            //print_r($landingPage);

        }

        $urlParams = [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => [$this->getFilter()->getRequestVar() => null],
            '_escape' => true,
        ];
        return $this->_url->getUrl('*/*/*', $urlParams);
    }
    /**
     * Get url for remove item from filter
     *
     * @return string
     */

    //support removals for multiURL
    public function getRemoveUrl()
    {      
     $debug = false;
     if(isset($_GET['debug'])){
         $debug = $_GET['debug'];
     }
         
        if($debug){
            echo '"" ></a><style>.ev_ln_filter .ev_ln_items li.item{display:block;}</style><br>';
          }
       // $query = [$this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue()];
        $suffix = $this->getUrlSuffix();
       

        $value = [];
        
        $filter = $this->getFilter();

        $value = $this->_filterModel->getFilterValue($filter);
          
        $itemValue = $this->getValue(); ///remove value


        if (is_array($this->getValue())) {
            $itemValue = implode('-', $this->getValue());
        }
        if (in_array($itemValue, $value)) {
            $value = array_diff($value, [$itemValue]);
        }

        $params['_query'] = [$filter->getRequestVar() => count($value) ? implode(',', $value) : $filter->getResetValue()];
      //  print_r($params['_query']);
        $params['_current'] = true;       //?  put example here
        $params['_use_rewrite'] = true;      //? put example here
        $params['_escape'] = true;       //? put example here

        //check if a landing page
        $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');
        $is_category = false;        
        if($this->getFilter()->getRequestVar() == "cat"){
         $is_category = true;    
        }

//here we check if the landing page is matchin the current link - so we can exclude and paramatize instead
        // eg /mattresses/single-mattresses - then on double link - it already has landing page for size
        //so we expect /mattress?choose_size=2131,3131
        if($this->getFilter()->getRequestVar() == "cat" && !$is_landing_page && 1==2){





            $url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



            $url_params_split = explode('?', $url);

            // $url = $landingPage['url_key'];
            $url_arr = explode('/', $url_params_split[0]);
            //print_r($url_arr);
                                                     //$suffix
            $remove_url_part = '/' . end($url_arr);
  
            $url = urldecode(
                $this->_url->getUrl(
                    '/*/*/*', [
                                '_current' => false,
                                '_use_rewrite' => true,
                                '_query' => ''
                            ]
                )
            );

            $url = str_replace($remove_url_part, '', $url);



            $url = $url.$suffix;

            // $url = ltrim($url, '/');
            if(isset($url_params_split[1])){
                $url = $url."?".$url_params_split[1];

            }
            //remove cat param
            $url_remove = parse_url($url);
                 if($debug){
      echo '[1]'.$url.'<a href="';
      }
        
            return $url;

        }
        /*edit*/

        //if we are on a current landing page


        if($is_landing_page) {
            $landingPage = $this->_coreRegistry->registry('elevate_landingpage_data');
        

            $remove_url_key = $landingPage['url_key'];
        }

        //we need to check if its a new landing page
        //$current_landing_page =  Mage::registry('current_landingpage');
        $current_parameters = $this->_request->getParams();



        $param_flip = [];
if(is_array($current_parameters)){
//unset category
  unset($current_parameters['landingpage_id']);

    try{
                         
        foreach($current_parameters AS $key => $val){

            $param_flip[$val] = $key;
        }

//$param_flip = array_flip($current_parameters);
    }
    catch(Exception $e){


    }

     
        if(array_key_exists($itemValue, $param_flip)){


            $check = $param_flip[$itemValue];
           //  "TRUE<br>";

            if(isset($_GET[$check])){
// "HAS FILTER";


            }
            else{


$url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



                $url_params_split = explode('?', $url);

                // $url = $landingPage['url_key'];
                $url_arr = explode('/', $url_params_split[0]);
                //print_r($url_arr);



              $url = $url_params_split[0];

                //cant just assume this - might not be end one - have to regen
                //Get Object Manager Instance

                $currentLandingAttributes = $this->_coreRegistry->registry('elevate_landingpage_attributes');
                $contactModel = $this->landingPage;

                $category = false;
                //  \Magento\Framework\Profiler::start('loadByAttributesCalc');
                if(is_array($currentLandingAttributes)){

                   // unset one


                    $currentLandingAttributes = array_flip($currentLandingAttributes);
                    unset($currentLandingAttributes[$itemValue]);
                    $currentLandingAttributes = array_flip($currentLandingAttributes);

                    $prepend = '';
                    $url_param = '';
                    $remove_url_part = false;
                   
                    $landingPage = $contactModel->loadByAttributesCalc($currentLandingAttributes, $category, false);
                    if(isset($landingPage['landingpage_id'])) {
                                               
             

                        //    if(is_numeric($landingPage['landingpage_id']) && count($count_vals) < 2) {
                        $url = $landingPage['url_key'];
                        $url_arr = explode('/', $url);
$prepend = '/';

//no need for suffix as landingpages dont support them
                         $suffix = '';

                        if(isset($url_params_split[1])){

                            $url_param = "?".$url_params_split[1];
                        }else{
                         
                        
                        
                        }
                        
                        
                        
                    }
                    else{
                        //its a landing page - but not a new one - so we need to strip
                        $remove_url_part = '/' . end($url_arr);
                             
       if($this->getFilter()->getRequestVar() == "cat"){

                                                     



            $url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



            $url_params_split = explode('?', $url);

            // $url = $landingPage['url_key'];
            $url_arr = explode('/', $url_params_split[0]);
            //print_r($url_arr);
                                                     //$suffix
            $remove_url_part = '/' . end($url_arr);
  
            $url = urldecode(
                $this->_url->getUrl(
                    '/*/*/*', [
                                '_current' => false,
                                '_use_rewrite' => true,
                                '_query' => ''
                            ]
                )
            );

            $url = str_replace($remove_url_part, '', $url);



            $url = $url.$suffix;

            // $url = ltrim($url, '/');
            if(isset($url_params_split[1])){
                $url = $url."?".$url_params_split[1];

            }
            //remove cat param
            $url_remove = parse_url($url);
                 if($debug){
      echo '[1c]'.$url.'<a href="';
      }
            
            return $url;

        }

                    }



      $new_url_params = [];
      
            $this_url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $url_params_split = explode('?', $this_url);
         
              if(isset($url_params_split[1])){
           $url_param_split_2 = explode('&', $url_params_split[1]); 
        //   print_r($url_param_split_2);
              foreach($url_param_split_2 AS $key => $val){
              
               $explode_vals = explode('=', $val);
              if(isset($explode_vals[0])){
             
              $new_url_params[$explode_vals[0]] = $explode_vals[1];
              }   
              }  
                             if(isset($url_params_split[0])){
            $url_arr = explode('/', $url_params_split[0]);
            }
              }
              
               $url_parameters = false;
               $url_parameters_build = [];
                  
        $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');
            if($is_landing_page){
              $landing_page_data = $this->_coreRegistry->registry('elevate_landingpage_data');
             $currentLandingAttributes = $this->_coreRegistry->registry('elevate_landingpage_attributes');
                  
                    $currentLandingAttributes = array_merge($currentLandingAttributes, $new_url_params);
                    
                            $currentLandingAttributes = $new_url_params;
                   foreach($currentLandingAttributes as $key =>$val){
                   $url_parameters_build[] = $key."=".$val;
                   }
                  // print_r($new_url_params);
            // $url = $landingPage['url_key'
            $url_parameters = implode('&', $url_parameters_build);
                  
            }
            $url_param  = '';
                        if($url_parameters){
                        $url_param = '?'.$url_parameters;
                        }
                
                
                 $category = $this->_coreRegistry->registry('current_category');//get current category
if($category){
        $url = $category->getUrl();
        $suffix = ''; //wipe suffix - already in category
}
 
                  
                  
                  
                       
                        
                 
                    $url = $prepend.$url.$suffix.$url_param;
                    
                    
                    
                    
                    
if($remove_url_part){
                    $url = str_replace($remove_url_part, '', $url);
}
if($debug){
      echo '[2]'.$url.'<a href="';
      }
                    return $url;
                    //print_r($landingPage);

                }

                // print_r($currentLandingAttributes);
             //

                ///end regen
                $remove_url_part = '/' . end($url_arr);
                
                if(isset($params['_query']['cat'])){
                unset($params['_query']['cat']);
                }
                     //print_r($params['_query']);
                $url = urldecode(
                    $this->_url->getUrl(
                        '/*/*/*', [
                                    '_current' => false,
                                    '_use_rewrite' => true,
                                    '_query' => $params['_query']
                                ]
                    )
                );

                $url = str_replace($remove_url_part, '', $url);



                $url = $url.$suffix;
                // $url = ltrim($url, '/');
if(isset($url_params_split[1])){
if(!empty($url_params_split[1])){
    $url = $url."?".$url_params_split[1];
    }

}
                    if($debug){
      echo '[3]'.$url.'<a href="';
      }
       
                return $url;
            }

        }


}



        /*end edit*/


if($this->getFilter()->getRequestVar() == "cat"){





            $url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


              $new_url_params = [];
            $url_params_split = explode('?', $url);
              if(isset($url_params_split[1])){
           $url_param_split_2 = explode('&', $url_params_split[1]);
              foreach($url_param_split_2 AS $key => $val){

              $explode_vals = explode('=', $val);
              $new_url_params[$explode_vals[0]] = $explode_vals[1];
              }
            }
            $url_arr = explode('/', $url_params_split[0]);

    $url = urldecode(
        $this->_url->getUrl(
            '/*/*/*', [
                        '_current' => false,
                        '_use_rewrite' => true,
                        '_query' => ''
                    ]
        )
    );
    if($debug) {
      //  return $this->_url->getUrl('*/*/*', $params);

    }


               $url_parameters = false;
               $url_parameters_build = [];
            if($is_landing_page){
              $landing_page_data = $this->_coreRegistry->registry('elevate_landingpage_data');
             $currentLandingAttributes = $this->_coreRegistry->registry('elevate_landingpage_attributes');


                    $currentLandingAttributes = array_merge($currentLandingAttributes, $new_url_params);


                   foreach($currentLandingAttributes as $key =>$val){
                   $url_parameters_build[] = $key."=".$val;
                   }
                  // print_r($new_url_params);
            // $url = $landingPage['url_key'
            $url_parameters = implode('&', $url_parameters_build);

                //$suffix
                $remove_url_part = '/' . end($url_arr);


                $url = str_replace($remove_url_part, '', $url);

                $remove_url_part2 = '/' . $url_arr[count($url_arr)-2];
                $url = str_replace($remove_url_part2, '', $url);

            }
            //print_r($url_arr);




            $url = $url.$suffix;

            // $url = ltrim($url, '/');
            if(($url_parameters)){
                $url = $url."?".$url_parameters;

            }
            //remove cat param
            $url_remove = parse_url($url);
                 if($debug){

print_r($params['_query']);



                 //    '_query' => $params['_query']
                     //$category = $this->_coreRegistry->registry('current_category');//get current category
                     //if($category){
                      //   $url = $category->getUrl();
                       //  $suffix = ''; //wipe suffix - already in category
                     //}



                     echo '[1b]'.$url.'<a href="';
      }

            return $url;

        }







         //default - 
        if($debug){
          echo '[4]'.$this->_url->getUrl('*/*/*', $params).'<a href="';
      }           
        return $this->_url->getUrl('*/*/*', $params);
    }





    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {

        if ($this->_request->getFullActionName() == 'catalogsearch_result_index') {



        $query = [
            $this->getFilter()->getRequestVar() => $this->getValue(),
            // exclude current page from urls
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];
        return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);

        }






        $suffix = $this->getUrlSuffix();
        //FIRMNESS - converts the URL to the correct lookups - TABLE REQUIRED FOR THIS
        $linkOverrides['mattress_firmness']['9072'] = 1; //1
        $linkOverrides['mattress_firmness']['9073'] = 2; //2
        $linkOverrides['mattress_firmness']['9074'] = 3; //3
        $linkOverrides['mattress_firmness']['9075'] = 4; //4
        $linkOverrides['mattress_firmness']['9076'] = 5; //5
        $linkOverrides['mattress_firmness']['9077'] = 6; //6
        $linkOverrides['mattress_firmness']['9078'] = 7; //7
        $linkOverrides['mattress_firmness']['9079'] = 8; //8
        $linkOverrides['mattress_firmness']['9080'] = 9; //9
        $linkOverrides['mattress_firmness']['9081'] = 10; //10
        $linkOverrides['mattress_firmness']['9082'] = 11; //11
        $linkOverrides['mattress_firmness']['9083'] = 12; //12
        $linkOverrides['mattress_firmness']['9084'] = 13; //13
        $linkOverrides['mattress_firmness']['9085'] = 14; //14
        $linkOverrides['mattress_firmness']['9086'] = 15; //15
        $linkOverrides['mattress_firmness']['9087'] = 16; //16
        $linkOverrides['mattress_firmness']['9088'] = 17; //17
        $linkOverrides['mattress_firmness']['9089'] = 18; //17

        $linkValue_lookup  = [];





        $value = [];
        $filter = $this->getFilter();
        $linkValue = $this->getValue();
        $requestVar = $filter->getRequestVar(); //e.g choose_size, mattress_firmness
        //do the adjustment here if mattress firmness


        if(array_key_exists($requestVar, $linkOverrides)){
            if(isset($linkOverrides[$requestVar][$linkValue])){
                $linkValue = $linkOverrides[$requestVar][$linkValue];
            }
        }

        $linkValue2 = '';

        if($requestVar == 'mattress_firmness'){


            $_firmnessRatingHelper = \Magento\Framework\App\ObjectManager::getInstance()->get("Elevate\Firmness\Helper\Data");

            $adjustment = $_firmnessRatingHelper->getBodyweightAdjustment();
            if(is_numeric($linkValue)) {
                $linkValue2 = (int)$linkValue;
                $linkValue = (int)$linkValue + (int)$adjustment;
            }
        }

        $linkValue_lookup[1] = 'soft';
        $linkValue_lookup[2] = 'soft';
        $linkValue_lookup[3] = 'soft';
        $linkValue_lookup[4] = 'medium_soft';
        $linkValue_lookup[5] = 'medium_soft';
        $linkValue_lookup[6] = 'medium_soft';
        $linkValue_lookup[7] = 'medium_soft';
        $linkValue_lookup[8] = 'medium';
        $linkValue_lookup[9] = 'medium';
        $linkValue_lookup[10] = 'medium';
        $linkValue_lookup[11] = 'medium_firm';
        $linkValue_lookup[12] = 'medium_firm';
        $linkValue_lookup[13] = 'medium_firm';
        $linkValue_lookup[14] = 'medium_firm';
        $linkValue_lookup[15] = 'medium_firm';
        $linkValue_lookup[16] = 'firm';
        $linkValue_lookup[17] = 'firm';
$oldLink = $linkValue;
        if(isset($linkValue_lookup[$linkValue2])){
            $linkValue = $linkValue_lookup[$linkValue2];

        }

          //value - numeric of the attribute - e.g 2367, 2369 (which are options of choose_size)
            if ($requestValue = $this->_request->getParam($requestVar)) {
                $value = explode(',', $requestValue);
            }
            if (!in_array($linkValue, $value)) {
                $value[] = $linkValue;
            }

         //make an array of the values
        $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');

        if($is_landing_page) {
            $landingPage = $this->_coreRegistry->registry('elevate_landingpage_data');
          
        }


        if (count($value) > 1) {

            $query = [
                $this->getFilter()->getRequestVar() => implode(',', $value),
                // exclude current page from urls
                $this->_htmlPagerBlock->getPageVarName() => null,
            ];






if($is_landing_page){


    $count_vals = $value;
    unset($count_vals['p']);

    if(isset($landingPage['landingpage_id'])){

    //    if(is_numeric($landingPage['landingpage_id']) && count($count_vals) < 2) {
        $url = $landingPage['url_key'];
            $url_arr = explode('/', $url);


            //for the remove part - we only need to remove the url if it is the exact landing page


        //we need to check if its a new landing page
        //$current_landing_page =  Mage::registry('current_landingpage');
        $current_parameters = $this->_request->getParams();

        $remove_end_part = false;
        if(is_array($current_parameters)) {

            //how are we going to check its part of the group
           // $param_flip = array_flip($current_parameters);

            foreach($current_parameters AS $key => $val){

                $param_flip[$val] = $key;
            }
            if (array_key_exists($linkValue, $param_flip)) {


                $check = $param_flip[$linkValue];
               //  "TRUE<br>";

                if (isset($_GET[$check])) {
                 //    "HAS FILTER";

                } else {

// "NO FILTER";
                }
            }

            $remove_end_part = true;

        }




     /*   https://m2.happybeds.co.uk/furniture/chests-of-drawers/grey
      we need tp remove the last part if the group is the same - see white / black in this example
// https://m2.happybeds.co.uk/furniture/chests-of-drawers/grey

     on this page for example - choose another colour - you need to strip the URL from the end

     but if you choose say number of draws it doesnt want to drop the grey

     so we need to figure out if this link is one of "grey" or any other
     //do we need to store the attribute code in the db for this? speed up
     //fallback
     */
     if($remove_end_part){
     
            $remove_url_part = '/' . end($url_arr);
     }

            $url = urldecode(
                $this->_url->getUrl(
                    '/*/*/*', [
                    '_current' => false,
                    '_use_rewrite' => true,
                    '_query' => ''
                ]
                )
            );

        if($remove_end_part) {
            $url = str_replace($remove_url_part, '', $url);
        }

            // $url = ltrim($url, '/');

        $request = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
        if(strpos($request,'?') !== false ){
            $query_string = substr($request,strpos($request,'?'));
        }
        else{
            $query_string = '';
        }

        /*dedpupe*/



            return $url.urldecode($query_string);
       // }
           // return "/".$url;




     //   }
    }

    return urldecode($this->_url->getUrl('/*/*/*', ['_current' => false, '_use_rewrite' => true, '_query' => $query]));

}

            return urldecode($this->_url->getUrl('* /* /* ', ['_current' => true, '_use_rewrite' => true, '_query' => $query]));
        }

        //if it is a new link - check if it has a landing page
        //we have checked the new links - so here - we can safely - just pull back the full url
       /* if($new_link) {

            $landingPage = Mage::getModel('layeredlanding/layeredlanding')->loadByAttributesCalc($attributes_array, $category, false);

            if (is_numeric($landingPage['layeredlanding_id'])) {
                $url = $landingPage['page_url'];
                $url = ltrim($url, '/');

                return "/" . $url;
            }
        }
*/



        $query = [
            $this->getFilter()->getRequestVar() => $linkValue,
            // exclude current page from urls
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];
        $this->getLinkParametersArray($query);

      //  print_r($this->_linkParameters);

        $currentLandingAttributes = $this->_coreRegistry->registry('elevate_landingpage_attributes');
//($currentLandingAttributes);
        //how shall we handle categories?
        if($this->getFilter()->getRequestVar() == "cat"){
            //return "CAT";
            $categoryId = $this->getValue();


           // if landing page - then add in the calcs

            $category = $this->_categoryFactory->create()->load($categoryId);

            $return = $category->getUrl();


            $contactModel = $this->landingPage;
            $containsAllValues = false;
if(is_array($currentLandingAttributes)){
            $containsAllValues = !array_diff($currentLandingAttributes, $this->_linkParameters);
}


            \Magento\Framework\Profiler::start('loadByAttributesCalc');
            if(is_array($currentLandingAttributes)){
              $this->_linkParameters = array_merge($this->_linkParameters, $currentLandingAttributes);
            }
         unset($this->_linkParameters['p']);


            $landingPage = $contactModel->loadByAttributesCalc($this->_linkParameters, $category->getId(), true);


            if(is_numeric($landingPage['landingpage_id'])) {

                $url = $landingPage['url_key'];

                $url = ltrim($url, '/');

                $request = $this->_url->getUrl(
                    '*/*/*', [
                    '_current' => true,
                    '_use_rewrite' => true,
                    '_query' => $currentLandingAttributes
                ]
                );






                if($containsAllValues){
                    if (strpos($request, '?') !== false) {
                        $query_string = substr($request, strpos($request, '?'));
                    } else {
                        $query_string = '';
                    }


                }
                if (!empty($query_string)) {
                    $url .= urldecode($query_string);
                }

                return '/'.$url;

            }
                //foreach($currentLandingAttributes AS $attribute_code => $option_id){
   // $this->request->setParam($attribute_code, $option_id);
//}


            $request = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $currentLandingAttributes]);
            if(strpos($request,'?') !== false ){
                $query_string = substr($request,strpos($request,'?'));
            }
            else{
                $query_string = '';
            }




            if(!empty($query_string)){
                $return .= urldecode($query_string);
            }


            return $return;
        }

        //gets the attributes into $this->_linkParameters for the link




      //Get Object Manager Instance

       $contactModel = $this->landingPage;

        $category = false;
        \Magento\Framework\Profiler::start('loadByAttributesCalc');
        if(is_array($currentLandingAttributes)){
        $this->_linkParameters = array_merge($this->_linkParameters, $currentLandingAttributes);
        }

       // print_r($currentLandingAttributes);
        $landingPage = $contactModel->loadByAttributesCalc($this->_linkParameters, $category, false);
        \Magento\Framework\Profiler::stop('loadByAttributesCalc');
        //$landingPage = $this->_coreRegistry->registry('elevate_landingpage_data');
        $count_vals = $value;
        unset($count_vals['p']);

     if(isset($landingPage['landingpage_id'])){


         if(is_array($currentLandingAttributes)){
         $currentLandingAttributes = array_flip($currentLandingAttributes);

         if(isset($landingPage['assigned_option_ids'])){
         foreach($landingPage['assigned_option_ids'] AS $key => $val){
             unset($currentLandingAttributes[$val]);

         }
         }
         }

  //       $return= '';
         $request = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $currentLandingAttributes]);
         if(strpos($request,'?') !== false ){
             $query_string = substr($request,strpos($request,'?'));
         }
         else{
             $query_string = '';
         }





       if(is_numeric($landingPage['landingpage_id'])
           && count($count_vals) < 2
           && $this->getFilter()->getRequestVar() != "price"){

            $url = $landingPage['url_key'];

            $url = ltrim($url, '/');


           if(!empty($query_string)){
               $url .= urldecode($query_string);
           }
//return "UEL";

            return "/".$url;




        }
     }
//print_r($query);

//return "LAST";
        return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

}
