<?php
namespace Elevate\LandingPages\Helper;

class Options extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $scopeConfig;
    protected $_objectManager = null;
    protected $_attributes        = [];
    protected $_eavSetup        = null;
    protected $_attributeFactory;
    protected $_eavSetupFactory;
    protected $_moduleDataSetupInterface;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory $attributeFactory,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->_attributeFactory = $attributeFactory;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_moduleDataSetupInterface = $moduleDataSetupInterface;
    }

    public function getAttribute($_code, $_force = false)
    {
        if(!isset($this->_attributes[$_code]) || $_force) {
            $_attribute = $this->_attributeFactory->create();
            $this->_attributes[$_code] = $_attribute->loadByCode(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE, $_code);
        }

        return $this->_attributes[$_code];
    }

    public function getEavSetup() {
        if(!$this->_eavSetup) {
            $this->_eavSetup = $this->_eavSetupFactory->create([
            'setup' => $this->_moduleDataSetupInterface]);
        }
        return $this->_eavSetup;
    }

    public function getAttributeOptionId($_code, $_value) {

                         


        $attribute = $this->getAttribute($_code);
        $optionArray = array();
        $optionArray['attribute_id'] = $attribute->getAttributeId();

        if(!$_value || !$attribute || !$attribute->getId()) {
            return '';
        }

        $optionId = '';
        $options = $attribute->getSource()->getAllOptions(false);


        if($options && count($options) > 0) {
            foreach($options as $option){
                if(trim(strtolower($option['label'])) ==  strtolower($_value)){
                    $optionId = $option['value'];
                    break;
                }
                if(trim(strtolower($option['value'])) ==  strtolower($_value)){
                    $optionId = $option['value'];
                    break;
                }
            }
        }

        if(!$optionId) {
            $option = [];
            $option['attribute_id'] = $attribute->getAttributeId();

            $option['value']['option'][0] = $_value;
            $option['value']['option'][1] = $_value;
            $option['order']['option'] = '';





            try{
           // $this->getEavSetup()->addAttributeOption($option);

            }
            catch(Exception $e){

             return false;
            }
            $attribute = $this->getAttribute($_code, true);
            $options = $attribute->getSource()->getAllOptions(false);

            if($options && count($options) > 0) {
                foreach($options as $option){
                    if(trim(strtolower($option['label'])) ==  strtolower($_value)){
                        $optionId = $option['value'];
                        break;
                    }
                }
            }
        }


        $optionArray['option_id'] = $optionId;

        if(!is_numeric($optionArray['option_id']) || !is_numeric($optionArray['attribute_id'])){
            return false;
        }

        return $optionArray;
    }
}