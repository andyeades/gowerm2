<?php
namespace Elevate\LandingPages\Model\Rewrite\Layer\Filter;

/**
 * Layer attribute filter
 */
class Attribute extends \Magento\Catalog\Model\Layer\Filter\Attribute
{

protected $_cacheHelper;
    protected $_appliedOptionIds;

    protected $_isFilter = true;
protected $linkOverrides = [];
    /**
     * @param ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Filter\StripTags $tagFilter
     * @param \Elevate\LandingPages\Helper\Cache $cacheHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Filter\StripTags $tagFilter,
        \Elevate\LandingPages\Helper\Cache $cacheHelper,
        array $data = []
    ) {
        $this->_cacheHelper = $cacheHelper;

        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $filterAttributeFactory, $string, $tagFilter, $data);


        $this->linkOverrides['mattress_firmness']['url']['soft'][] = -1;
        $this->linkOverrides['mattress_firmness']['url']['soft'][] = 0;
        $this->linkOverrides['mattress_firmness']['url']['soft'][] = 1;
        $this->linkOverrides['mattress_firmness']['url']['soft'][] = 2;
        $this->linkOverrides['mattress_firmness']['url']['soft'][] = 3;

        $this->linkOverrides['mattress_firmness']['url']['medium_soft'][] = 3;
        $this->linkOverrides['mattress_firmness']['url']['medium_soft'][] = 4;
        $this->linkOverrides['mattress_firmness']['url']['medium_soft'][] = 5;
        $this->linkOverrides['mattress_firmness']['url']['medium_soft'][] = 6;
        $this->linkOverrides['mattress_firmness']['url']['medium_soft'][] = 7;

        $this->linkOverrides['mattress_firmness']['url']['medium'][] = 7;
        $this->linkOverrides['mattress_firmness']['url']['medium'][] = 8;
        $this->linkOverrides['mattress_firmness']['url']['medium'][] = 9;
        $this->linkOverrides['mattress_firmness']['url']['medium'][] = 10;
        $this->linkOverrides['mattress_firmness']['url']['medium'][] = 11;

        $this->linkOverrides['mattress_firmness']['url']['medium_firm'][] = 11;
        $this->linkOverrides['mattress_firmness']['url']['medium_firm'][] = 12;
        $this->linkOverrides['mattress_firmness']['url']['medium_firm'][] = 13;
        $this->linkOverrides['mattress_firmness']['url']['medium_firm'][] = 14;
        $this->linkOverrides['mattress_firmness']['url']['medium_firm'][] = 15;

        $this->linkOverrides['mattress_firmness']['url']['firm'][] = 15;
        $this->linkOverrides['mattress_firmness']['url']['firm'][] = 16;
        $this->linkOverrides['mattress_firmness']['url']['firm'][] = 17;
        $this->linkOverrides['mattress_firmness']['url']['firm'][] = 18;
        $this->linkOverrides['mattress_firmness']['url']['firm'][] = 19;

        $this->linkOverrides['mattress_firmness']['label']['soft'] = 'Soft';
        $this->linkOverrides['mattress_firmness']['label']['medium_soft'] = 'Soft / Medium';
        $this->linkOverrides['mattress_firmness']['label']['medium'] = 'Medium';
        $this->linkOverrides['mattress_firmness']['label']['medium_firm'] = 'Medium / Firm';
        $this->linkOverrides['mattress_firmness']['label']['firm'] = 'Firm';

    }

    public function convertArray($input, $vals){



        $_firmnessRatingHelper = \Magento\Framework\App\ObjectManager::getInstance()->get("Elevate\LandingPages\Firmness\Helper\Data");

        $adjustment = $_firmnessRatingHelper->getBodyweightAdjustment();


        //$parex = explode(',', $input);
        $paramjoin = [];
        $param = [];

          
        if(count($vals) > 0){
                foreach ($vals AS $key => $val) {




if(!empty($val)){

    if(isset($this->linkOverrides['mattress_firmness']['label'][$val])){
     
    $this->getLayer()->getState()->addFilter($this->_createItem($this->linkOverrides['mattress_firmness']['label'][$val], $val));

    }
    if(isset($this->linkOverrides[$input]['url'][$val])){
    foreach ($this->linkOverrides[$input]['url'][$val] AS $akey => $aval) {
                    $paramjoin[] = ($aval - $adjustment);

                }
    }

}
}
/*
            if (is_array($paramjoin)) {
                if (!empty($paramjoin)) {
                    $param = implode(',', $paramjoin);
                }
            }
*/
        }
        return $paramjoin;
    }




    /**
     * Apply attribute option filter to product collection
     *
     * @param   \Magento\Framework\App\RequestInterface $request
     * @return  $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {


        //filter attribute value, can be single word or comma seperated
        $filter = explode(',', $request->getParam($this->_requestVar));


        $attributeValue = $request->getParam($this->_requestVar);

        if (empty($attributeValue)) {
            $this->_isFilter = false;

           // return $this;
        }
              
        //get any filter overrides - like firmness
        if (array_key_exists($this->_requestVar, $this->linkOverrides)) {
            $param = $this->convertArray($this->_requestVar, $filter);
          
            foreach($param as $key => $val){

             
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                $_product = $objectManager->create('Magento\Catalog\Model\Product');



                $attr = $_product->getResource()->getAttribute('mattress_firmness');
                if ($attr->usesSource()) {
                    $val = $attr->getSource()->getOptionId($val);

                }
                       
                $this->_appliedOptionIds[] = $val;
            }
        }



        $attrCode = $this->getAttributeModel()->getAttributeCode();

        $state = $this->getLayer()->getState();

        foreach ($filter as $value) {


if(!is_numeric($value)){


    $value = $this->_cacheHelper->getOptionId($attrCode, $value);
    //echo "VAL".$value."<br>";
}


if(is_numeric($value)){
            $text = $this->getOptionText($value);




}
else{

    $text = ucwords(str_replace('-', ' ', $value));
}



            if($this->_requestVar == 'structure'){

               // echo $value."<br>";

               // echo "<pre>";
               // print_r($filter);
               // echo "</pre>";
               // exit;
            }

            if($this->_requestVar !== 'mattress_firmness'){
            if ($value && strlen($text)) {
                $this->_appliedOptionIds[] = $value;
                //$this->_getResource()->applyFilterToCollection($this, $value);
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $value));
            }
                }
        }



        //  print_r($applyOptionIds);
        $attribute = $this->getAttributeModel();



        $productCollection = $this->getLayer()->getProductCollection();



        if (!empty($this->_appliedOptionIds)) {

            //remove empty

            //remove this and this breaks
            //https://m2.happybeds.co.uk/mattresses?mattress_firmness=soft
            foreach($this->_appliedOptionIds AS $key => $val){
                if(empty($val)){
                    unset($this->_appliedOptionIds[$key]);
                }

            }

            //reset array keys - as ES6 doesnt like not having some
            $this->_appliedOptionIds = array_values($this->_appliedOptionIds);
          //  print_r( $this->_appliedOptionIds);

            if ($this->_cacheHelper->isElasticSearchEngine()) {
                $productCollection->addFieldToFilter($attribute->getAttributeCode(), $this->_appliedOptionIds);
            } else {
                $productCollection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $this->_appliedOptionIds]);
            }

         //   $productCollection->addFieldToFilter($attribute->getAttributeCode(), $this->_appliedOptionIds);
               // $productCollection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $this->_appliedOptionIds]);

        }



        return $this;
    }
    /**$tagFilter
     * Get data array for building attribute filter items
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */

    /**
     * Get data array for building attribute filter items
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */
    protected function _getItemsData() {

        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();


      //make the filters dissapear if one is selected // except firmness
        if(!$attribute->getIsMultiselect() && $this->_isFilter && $attribute->getAttributeCode() != 'mattress_firmness') {
            return [];
        }



        $data = [];

        $cacheId = $this->_cacheHelper->getId("elevate_landingpages_" . $this->_requestVar);


        if ($cached_data = $this->_cacheHelper->load($cacheId)) {



          //return $cached_data;
        }
        else{


            //exit;

        }


        /** @var \Elevate\LandingPages\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();


        $optionsFacetedData = $productCollection->getFacetedData($attribute->getAttributeCode());



        $options = $attribute->getFrontend()->getSelectOptions();

        $optionsCount = $this->_getResource()->getCount($this);



        foreach ($options as $option) {



            if (is_array($option['value'])) {
                continue;
            }
            $optionCount = isset($optionsFacetedData[$option['value']]['count']) ? (int)$optionsFacetedData[$option['value']]['count'] : 0;


            if ($this->string->strlen($optionCount) && $optionCount > 0) {
                // Check filter type
                if ($this->getAttributeIsFilterable($attribute) == self::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS) {



                $data[] = array(
                            'label' => $this->tagFilter->filter($option['label']),
                            'value' => $option['value'],
                            'count' =>  $optionCount //$optionsCount[$option['value']]
                        );

                } else {
                    $data[] = array(
                        'label' => $this->tagFilter->filter($option['label']),
                        'value' => $option['value'],
                        'count' => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']]: 0
                    );
                }
            }
        }


        //$helper = $this->helper('Elevate\Promotions\Helper\Data');
        $show_more_excludes = $this->_cacheHelper->getConfig('elevate_landingpages/categories/sort_by_count');
        $show_more_excludes_exp = array_flip(explode(',', $show_more_excludes));
        $sort_by_count_type = $this->_cacheHelper->getConfig('elevate_landingpages/categories/sort_by_count_type');


        //print_r($show_more_excludes_exp);
        //sort_by_count
        //echo $attribute->getAttributeCode();
if($sort_by_count_type == 'include'){
        if(array_key_exists($attribute->getAttributeCode(), $show_more_excludes_exp)){

        usort($data, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        }

}

        if($sort_by_count_type == 'exclude'){
            if(array_key_exists($attribute->getAttributeCode(), $show_more_excludes_exp)){

            }
            else{

                usort($data, function($a, $b) {
                    return $b['count'] <=> $a['count'];
                });
            }

        }


       $this->_cacheHelper->save($data, $cacheId);



        return $data; //$this->itemDataBuilder->build();
    }
}