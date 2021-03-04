<?php

namespace Elevate\LandingPages\Block\Navigation;

/**
 * Layered navigation state
 *
 * @api
 * @since 100.0.2
 */
class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    /**
     * @var string
     */
    protected $_template = 'Elevate_LandingPages::layer/state.phtml';

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;
    
        protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context,$layerResolver, $data);
    }

    public function getCurrentCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }



        /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function getClearUrl()
    {



        if($this->getCurrentCategory()){

            $level = $this->getCurrentCategory()->getParentCategory()->getLevel();


            if($level > 1){
                return $this->getCurrentCategory()->getParentCategory()->getUrl();
            }
            else{
                //  echo $this->escapeHtml($_category->getName());
            }

            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $category= $_objectManager->create('Magento\Catalog\Model\Category')->load($this->getCurrentCategory()->getId());

            $url = $category->getUrl();
            return $url;

        }







        $filterState = [];
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
                  
        $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');


//here we check if the landing page is matchin the current link - so we can exclude and paramatize instead
        // eg /mattresses/single-mattresses - then on double link - it already has landing page for size
        //so we expect /mattress?choose_size=2131,3131

        /*edit*/
        if($is_landing_page) {
            $landingPage = $this->_coreRegistry->registry('elevate_landingpage_data');
         //   echo "<pre>";
            //  print_r($landingPage);
        //    echo "</pre>";
            $remove_url_key = $landingPage['url_key'];
            
         $suffix = '';   

$url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



                $url_params_split = explode('?', $url);

                // $url = $landingPage['url_key'];
                $url_arr = explode('/', $url_params_split[0]);
                //print_r($url_arr);
                $remove_url_part = '/' . end($url_arr);

                $url = urldecode(
                    $this->_urlBuilder->getUrl(
                        '/*/*/*', [
                                    '_current' => false,
                                    '_use_rewrite' => true,
                                    '_query' => $filterState
                                ]
                    )
                );

                $url = str_replace($remove_url_part, '', $url);



                $url = $url.$suffix;
                // $url = ltrim($url, '/');
if(isset($url_params_split[1])){
    $url = $url."?".$url_params_split[1];

}
return $url;
        }

        
     
        
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->_urlBuilder->getUrl('*/*/*', $params);
    }

}
