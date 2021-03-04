<?php

namespace Elevate\Microsite\Helper;

use Elevate\Microsite\Model\Rule as RuleModel;
use Magento\Framework\App\Action\Action;
use Elevate\Microsite\Model\Rule\ForbiddenActionOptionsProvider;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Cms\Helper\Page
     */
    private $pageHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;
    private $wishlist;
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    private $sessionFactory;
    private $_CategoryCollection;

    private $dataStatic = ['platinum_name' => "PLATINUM" ,
                           'pleasure_name' => 'PLEASURE' ,
                           'multiplay_name' => 'THE MULTIPLAY' ,
                           'attr_platinum' => 'platinum_icon',
                           'attr_pleasure' => 'pleasure_icon',
                           'attr_multiplay' => 'the_multiplay_icon'
    ];

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Helper\Page $pageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Wishlist\Model\Wishlist $wishlist,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $CategoryCollection
    ) {
        parent::__construct($context);
        $this->jsonEncoder = $jsonEncoder;
        $this->wishlist = $wishlist;
        $this->pageHelper = $pageHelper;
        $this->storeManager = $storeManager;
        $this->sessionFactory = $sessionFactory;
        $this->_CategoryCollection = $CategoryCollection;
    }

    public function getModuleConfig($path)
    {
        return $this->scopeConfig->getValue('elevate_microsite/' . $path);
    }

    public function getModuleStoreConfig($path)
    {
        return $this->scopeConfig->getValue('elevate_microsite/' . $path,
                                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isModuleEnabled()
    {
        return true;
        return $this->getModuleConfig('general/enabled') && $this->isModuleOutputEnabled();
    }

    /**
     * @param Action    $controller
     * @param RuleModel $rule
     */
    public function setRedirect(Action $controller, RuleModel $rule)
    {
        if ($rule->getAllowDirectLinks()) {
            return;
        }

        /** @var Action $controller */
        $controller->getActionFlag()->set('', Action::FLAG_NO_DISPATCH, true);
        $controller->getActionFlag()->set('', Action::FLAG_NO_POST_DISPATCH, true);
        $controller->getResponse()->setStatusCode(\Zend\Http\Response::STATUS_CODE_401);
        $controller->getResponse()->setRedirect('404');

        if ($rule->getForbiddenAction() == ForbiddenActionOptionsProvider::REDIRECT_TO_PAGE) {
            $url = $this->pageHelper->getPageUrl($rule->getForbiddenPageId());

            if ($url) {
                $controller->getResponse()->setRedirect($url);
            }
        }
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {
        return $this->sessionFactory->create();
    }
    public function getValuStatic ($key = null){
        if ($key == null) {
            return $this->dataStatic;
        }
        return isset($this->dataStatic[$key]) ? $this->dataStatic[$key] : "";
    }
    public function getRulesCustomerBySession(){
        $customerSession = $this->getCustomerSession();

        if (!$customerSession->isLoggedIn()) return false;

        $groupId = $customerSession->getCustomer()->getData("group_id");

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $ruleGroupTable = $resource->getTableName('elevate_microsite_rule_customer_group');
        $sql = "Select * FROM " . $ruleGroupTable . " where customer_group_id = ". $groupId;
        $rule_id = $connection->fetchOne($sql);
        if (!$rule_id) return false;
        $ruleInfo = $objectManager->get('Elevate\Microsite\Model\RuleRepository');
        return $ruleInfo->get($rule_id);
    }
    public function getProductConditionsRule(){
        $ruleInfo = $this->getRulesCustomerBySession();
        if (!$ruleInfo) return false;
        $str = $ruleInfo->getData("conditions_serialized");
        $arr = unserialize($str);

        $result = "";
        if (isset($arr['conditions'])) {
            foreach ($arr['conditions'] as $value){
                if ($value['type'] == "Magento\CatalogRule\Model\Rule\Condition\Product" && $value['attribute'] == "sku"){
                    $result = $value['value'];
                    break;
                }
            }

        }
        return $result;

    }
    function getRuleProductInAdmin($sku){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('elevate_microsite_rule');
        $sql = 'SELECT * FROM ' . $tableName . ' where conditions_serialized like "%'. $sku . '%"';
        //$sql = "SELECT * FROM " . $tableName . " where conditions_serialized like '%". $sku . "%'";
        $result = $connection->fetchAll($sql);
        $data = [];
        if (count($result) == 0) return $data;
        foreach  ($result as $val){
            $data[] = $val['rule_id'];
        }
        return $data;
    }
    function getRule (){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('elevate_microsite_rule');
        $sql = "SELECT * FROM " . $tableName;
        $result = $connection->fetchAll($sql);

        return $result;
    }
    function getAllMemberShipAndServiceBagsProduct($item){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $objectManager->get('Magento\Catalog\Model\Product')->load($item->getData("entity_id"));

        $result = [];
        $displayPlatinum    = $item->getData($this->dataStatic['attr_platinum']);
        $displayPleasure    = $item->getData($this->dataStatic['attr_pleasure']);
        $displayTheMultiple = $item->getData($this->dataStatic['attr_multiplay']);
        if ($displayPlatinum === "1"){
            $result [] = $this->dataStatic['platinum_name'];
        }
        if ($displayPleasure === "1") {
            $result [] = $this->dataStatic['pleasure_name'];
        }
        if ($displayTheMultiple === "1"){
            $result [] = $this->dataStatic['multiplay_name'];
        }
        return $result;
    }

    function getAllMemberShipAndServiceBagsProductFavorites($item){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $objectManager->get('Magento\Catalog\Model\Product')->load($item->getId());
        return $this->getAllMemberShipAndServiceBagsProduct($_product);
    }
    function FilterItemsByItemListAndRule($itemsList){

        $productAllow  = $this->getProductConditionsRule();
        $productAllowArray = array ();
        if (!empty($productAllow)){
            $productAllowArray = array_map('trim',explode(",",$productAllow));
        }

        if (empty($productAllowArray)) return $itemsList;

        $arr_id = [];
        foreach ($itemsList as $_item){
            $sku = $_item->getSku();
            if (in_array($_item->getSku(),$productAllowArray)){

                $arr_id[] = $_item->getId();
            }
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollectionFactory = $objectManager->get("Magento\Catalog\Model\ResourceModel\Product\CollectionFactory");
        $data = $productCollectionFactory
            ->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $arr_id]);

        return $data;
    }

    function checkDisplayButtonProductDetail($_product){
        $_customerSession    = $this->getCustomerSession();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_customerGroup      = $objectManager->get('\Magento\Customer\Model\Group')->load($_customerSession->getCustomer()->getData("group_id"));
        $_customerGroupCode  = $_customerGroup->getCustomerGroupCode();
        $_productSku = $_product->getSku();
        $categoryIds = $_product->getCategoryIds();
        $_arrLuxury = ["LUXURY CARD"];
        if (in_array( strtoupper(trim($_productSku)) ,$_arrLuxury )) {
            if($_customerGroupCode != "GOLD MEMBER"){
                return true;
            }
        }

        $limitProduct        = $this->getAllMemberShipAndServiceBagsProduct($_product);
        $_amastyHelper = $objectManager->get('Elevate\Microsite\Helper\Data');
        $result = false;

        if (empty($limitProduct)) {
            $result = true;
        }
        else {
            foreach ($limitProduct as $value){
                if (strpos($_customerGroupCode , $value) !== false){

                    $result = true;
                    break;
                }
            }
        }

        if ($result == true){

            $_categoryHelper = $objectManager->get('SkyPremium\Catalog\Helper\GetNameOfCategory');
            $_CategoryLimit  = $_categoryHelper->getCategoryDisableButtonReserve();

            if (empty($_CategoryLimit)){
                return true;
            }
            if (!empty($categoryIds)){
                $categoryIds = $_product->getCategoryIds();

                $Categorycollection = $this->_CategoryCollection
                    ->create()->addAttributeToSelect('*')
                    ->addAttributeToFilter('entity_id',$categoryIds);
                foreach ($Categorycollection as $category){

                    $nameCategory  = strtoupper(trim($category->getData("name")));
                    if ($nameCategory === strtoupper("Travel")){
                        return true;
                    }
                }

                foreach ($Categorycollection as $category){

                    $nameCategory  = strtoupper(trim($category->getData("name")));
                    if (in_array($nameCategory,$_CategoryLimit)){
                        return false;
                    }
                }
            }else {
                return false;
            }

        }
        return $result;

    }
    public function checkFavoriteItems($itemId){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_amastyHelper = $objectManager->get('Elevate\Microsite\Helper\Data');


        $_customerSession    = $this->getCustomerSession();

        $customer_id =  $_customerSession->getCustomer()->getId();
        $wishlist_collection = $this->wishlist->loadByCustomerId($customer_id, true)->getItemCollection();

        $result = false;


        foreach ($wishlist_collection as $value){

            try {
                if ($value->getProduct()->getId() == $itemId){
                    $result = true;
                    break;
                }
            } catch (\Exception $e) {

            }
        }


        return $result;

    }

    public function _getUrl($route, $params = [])
    {
        return parent::_getUrl($route, $params);
    }

    function getAllMemberShipAndServiceBagsCustomer(){
        $customerresult = $this->getRulesCustomerBySession();

        $name = trim($customerresult->getName());
        $pos = strpos($name, "+");
        if ($pos){
            $arr = explode("+",$name);
        }else{
            $arr = array($name);
        }
        return $arr;
    }
}
