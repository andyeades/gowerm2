<?php
namespace Elevate\LandingPages\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Elevate\LandingPages\Helper\Data as CustomRouteHelper;

class Router implements RouterInterface {
    /**
     * @var bool
     */
    private $dispatched = false;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var CustomRouteHelper
     */
    protected $helper;

    /**
     * Router constructor.
     *
     * @param ActionFactory         $actionFactory
     * @param EventManagerInterface $eventManager
     * @param CustomRouteHelper     $helper
     */
    public function __construct(
        ActionFactory $actionFactory,
        EventManagerInterface $eventManager,
        CustomRouteHelper $helper
    ) {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->helper = $helper;
    }

    /**
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request) {
       return false;
        echo "MATCH ROUTER LANDING PAGES MODULE";
        //CAN POSSIBLY DELETE THIS NOW USING THE REWRITES

        exit;
        $landingRoute = false;
        /** @var \Magento\Framework\App\Request\Http $request */
        if (!$this->dispatched) {
            $identifier = trim($request->getPathInfo(), '/');
            $this->eventManager->dispatch(
                'core_controller_router_match_before', [
                'router'    => $this,
                'condition' => new DataObject(
                    [
                        'identifier' => $identifier,
                        'continue'   => true
                    ]
                )
            ]
            );

            $route = $this->helper->getModuleRoute();

            if (($route !== '' && $identifier === $route) || 1 == 1) {

                $identifier = $request->getPathInfo();

                $url = ltrim($identifier, '/');

                //Get Object Manager Instance
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $contactModel = $objectManager->create('Elevate\LandingPages\Model\LandingPage');

                $collection = $contactModel->getCollection()->addFieldToFilter('url_key', array('eq' => $url));

                foreach ($collection as $item) {
                    $landingPage = $item->getData();
                    $landingRoute = true;
                    break;
                    //  echo '<pre>';
                    //      print_r($item->getData());
                    //      echo '</pre>';
                }

                if (!$landingRoute) {
                    return false;
                }

                //we must have a category id to match on, so we can use the category controllers
                if (isset($landingPage['category_ids'])) {
                    $categoryIdsValue = $landingPage['category_ids'];
                    $categoryIds = explode(',', $categoryIdsValue);
                    $firstCategoryId = $categoryIds[0];
                }

                //if there is no category - then we cant show the landing page
                if (!is_numeric($firstCategoryId)) {
                    return false;
                }

                //behave like category - shows
                if (isset($landingPage['behave_like_category'])) {
                    $behave_like_category_id = $landingPage['behave_like_category'];
                    if (is_numeric($behave_like_category_id) && $behave_like_category_id > 0) {

                        $firstCategoryId = $behave_like_category_id;
                    }

                }

                //     $request->setRouteName('catalog')
                //->setModuleName('catalog')
                // ->setControllerName('category')
                //  ->setActionName('view')

                $request->setModuleName('elevate_landingpages')->setControllerName('index')->setActionName('index')->setParam('id', $firstCategoryId);

                //  http://stage.crucialfitness.co.uk/proteins/test?flavour=12

                //set the attributes
                if (isset($landingPage)) {
                    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                    $connection = $resource->getConnection();

                    $attributes = $connection->fetchPairs("SELECT attribute_id,option_id FROM elevate_landingpages_attributes WHERE landingpage_landingpage_id = ?", array($landingPage['landingpage_id']));
                    foreach ($attributes AS $attribute_id => $option_id) {
                        $eavModel = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
                        $attr = $eavModel->load($attribute_id);
                        $attributeCode = $eavModel->getAttributeCode();//Get attribute code from its id

                        $request->setParam($attributeCode, $option_id);

                    }

                    if (isset($landingPage)) {
                        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                        $connection = $resource->getConnection();

                        $attributes = $connection->fetchPairs("SELECT attribute_id,option_id FROM elevate_landingpages_attributes WHERE landingpage_landingpage_id = ?", array($landingPage['landingpage_id']));
                        foreach ($attributes AS $attribute_id => $option_id) {
                            $eavModel = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
                            $attr = $eavModel->load($attribute_id);
                            $attributeCode = $eavModel->getAttributeCode();//Get attribute code from its id

                            $request->setParam($attributeCode, $option_id);

                        }

                    }
                    //  $request->setModuleName('elevate_landingpages')
                    //  ->setControllerName('index')
                    //  ->setActionName('index');
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                    $this->dispatched = true;

                    return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
                }

                return NULL;
            }
        }
    }
}
