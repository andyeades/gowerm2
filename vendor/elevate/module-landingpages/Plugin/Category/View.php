<?php

namespace Elevate\LandingPages\Plugin\Category;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class View
 *
 * @package Elevate\LandingPages\Plugin\Category
 */
class View
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;
    /**
     * @var UrlInterface
     */
    protected $_storeManager;

    protected $_registry;

    protected $_coreRegistry;
protected $_filterProvider;



    /**
     * View constructor.
     *
     * @param JsonFactory  $resultJsonFactory
     * @param UrlInterface $_storeManager
     * @param PageFactory  $pageFactory
     */
    public function __construct(
        JsonFactory $resultJsonFactory,
        \Magento\Store\Model\StoreManagerInterface $_storeManager,
        PageFactory $pageFactory,
        Registry $registry,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
        
    ) {
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_storeManager = $_storeManager;
        $this->pageFactory = $pageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_registry = $registry;
        $this->_filterProvider = $filterProvider;
  
    }

    /**
     * @param Action $subject
     * @param        $page
     *
     * @return $this|void
     */
    public function afterExecute(
        Action $subject,
        $page
    ) {
        $response = $page;
        if ($response instanceof Page) {
            if ($subject->getRequest()->getParam('isAjax') == 1) {
                $subject->getRequest()->getQuery()->set('isAjax', null);
                $requestUri = $subject->getRequest()->getRequestUri();
                $requestUri = preg_replace('/(\?|&)isAjax=1/', '', $requestUri);
                $subject->getRequest()->setRequestUri($requestUri);

                //get the product list
                $productsList = $response->getLayout()->getBlock('category.products.list');
                $listMode = $subject->getRequest()->getParam('product_list_mode');
                if ($listMode) {
                    $productsList->getChildBlock('product_list_toolbar')->setData('_current_grid_mode', $listMode);
                }
                //

                $filterProvider = $this->_filterProvider;
                $storeManager = $this->_storeManager;

                //get the left navigation
                // $leftNav = $response->getLayout()->getBlock('catalog.leftnav')->toHtml();
                //echo "TEST";
                //$title = $response->getLayout()->getBlock('category.name')->toHtml();

                $category = $this->_registry->registry('current_category');//get current category

                $ev_landingpages_top_desc_type = $category->getData('ev_landingpages_top_desc_type');
                $ev_landingpages_top_desc_ajax = $category->getData('ev_landingpages_top_desc_ajax');

                $categoryDescription = '';

                if ($ev_landingpages_top_desc_type == 'onload_default_ajax_default') {
                    $categoryDescription = $response->getLayout()->getBlock('category.description')->toHtml();
                } elseif ($ev_landingpages_top_desc_type == 'onload_default_ajax_ajax' || $ev_landingpages_top_desc_type == 'onload_no_ajax_ajax') {
                    $categoryDescription = $ev_landingpages_top_desc_ajax;
                } elseif ($ev_landingpages_top_desc_type == 'onload_default_ajax_no') {
                    $categoryDescription = '';
                } else {
                    $categoryDescription = $response->getLayout()->getBlock('category.description')->toHtml();
                }

                $categoryDescription = $filterProvider->getBlockFilter()->setStoreId($storeManager->getStore()->getId())->filter($categoryDescription);

                //echo $category->getId();
                $title = $category->getName();

                /*
                    array(
                                'value' => 'onload_default_ajax_no',
                                'label' => 'Onload = Default Description, Ajax = No Description',
                            ),
                            array(
                                'value' => 'onload_default_ajax_default',
                                'label' => 'Onload = Default Description, Ajax = Default Description',
                            ),
                            array(
                                'value' => 'onload_default_ajax_ajax',
                                'label' => 'Onload = Default Description, Ajax = Ajax Description',
                            ),
                            array(
                                'value' => 'onload_no_ajax_ajax',
                                'label' => 'Onload = No Description, Ajax = Ajax Description',
                            )

                */

                /* this needs putting into a helper same as need to do for bottom_description.phtml */
                $ev_landingpages_btm_desc = $category->getData('ev_landingpages_btm_desc');
                $ev_landingpages_btm_desc_type = $category->getData('ev_landingpages_btm_desc_type');
                $ev_landingpages_btm_desc_ajax = $category->getData('ev_landingpages_btm_desc_ajax');

                $bottom_description = '';

                if ($ev_landingpages_btm_desc_type == 'onload_default_ajax_default') {
                    $bottom_description = $ev_landingpages_btm_desc;
                } elseif ($ev_landingpages_btm_desc_type == 'onload_default_ajax_ajax' || $ev_landingpages_btm_desc_type == 'onload_no_ajax_ajax') {
                    $bottom_description = $ev_landingpages_btm_desc_ajax;
                } elseif ($ev_landingpages_btm_desc_type == 'onload_default_ajax_no') {
                    $bottom_description = '';
                }
                $bottom_description = $filterProvider->getBlockFilter()->setStoreId($storeManager->getStore()->getId())->filter($bottom_description);

                /* this needs putting into a helper same as need to do for bottom_description.phtml */

                $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');

                if ($is_landing_page) {
                    //get landing page description - need to check how this is populating on the non ajax version
                    $landing_page_data = $this->_coreRegistry->registry('elevate_landingpage_data');
                    $categoryDescription = $landing_page_data['page_top_description'];
                         $bottom_description = $landing_page_data['page_bottom_description'];
                    $title = $landing_page_data['page_title'];
                    // print_r($landing_page_data);
                }
                $productsList = $productsList->toHtml();
                $leftNav = $response->getLayout()->getBlock('catalog.leftnav')->toHtml();

                $breadcrumbs_pre = $response->getLayout()->getBlock('breadcrumbs');
                /* Fixes Error where block is removed? - RJ 16/3/2021 */
                if (!empty($breadcrumbs_pre)) {
                    $breadcrumbs = $breadcrumbs_pre->toHtml();
                } else {
                    $breadcrumbs = false;
                }
$bottom_description = '';
                return $this->_resultJsonFactory->create()->setData(
                    [
                        'success' => true,
                        'html'    => [
                            'title'              => $title,
                            'products_list'      => $productsList,
                            'left_nav'           => $leftNav,
                            'top_description'    => $categoryDescription,
                            'bottom_description' => $bottom_description,
                            'breadcrumbs'        => $breadcrumbs
                        ]
                    ]
                );
            }
        }

        return $response;
    }
}
