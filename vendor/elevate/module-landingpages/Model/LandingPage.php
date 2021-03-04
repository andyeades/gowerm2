<?php
namespace Elevate\LandingPages\Model;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\App\ResourceConnection;

class LandingPage extends \Magento\Framework\Model\AbstractModel implements \Elevate\LandingPages\Api\Data\LandingPageInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'elevate_landingpages_landingpage';
    protected $_options;
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    public $dateTime;

    protected $_optionsHelper;
    private $resourceConnection;
    protected $_category;
    // public $_sqlStatement = [];
    //    public $_sqlValues = [];

    /**
     * Post constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Mageplaza\Blog\Helper\Data $helperData
     * @param \Mageplaza\Blog\Model\TrafficFactory $trafficFactory
     * @param \Mageplaza\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory
     * @param \Mageplaza\Blog\Model\ResourceModel\Topic\CollectionFactory $topicCollectionFactory
     * @param \Mageplaza\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Mageplaza\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $dateTime,
        \Elevate\LandingPages\Model\Attributes\Options $options,
        \Elevate\LandingPages\Helper\Options $optionsHelper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        ResourceConnection $resourceConnection,
        \Magento\Catalog\Model\Category $category,
        array $data = []
    )
    {
        $this->dateTime                  = $dateTime;
        $this->_options        = $options;
        $this->_optionsHelper = $optionsHelper;
        $this->_category = $category;
        $this->resourceConnection = $resourceConnection;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    protected function _construct(

    )
    {
        $this->_init('Elevate\LandingPages\Model\ResourceModel\LandingPage');

    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    public function getStores()
    {


        $storeArr = explode(',', $this->hasData('store_ids'));

        return (array)$storeArr;
    }
    public function loadByAttributesCalc($attributes_array, $category=false)
    {

        if(!is_array($attributes_array)){
            return false;
        }
        $sqlStatement = [];
        $sqlValues = [];
        $option_ids = [];
        $sqlWhere = [];
        //unset category - needed here?
        if(isset($attributes_array['category'])){
          //  unset($attributes_array['category']);
        }

        if(isset($attributes_array['cat'])){
         //   unset($attributes_array['cat']);
        }


        //	$tablePrefix = (string)Mage::getConfig()->getTablePrefix();

        $increment = 0;


        foreach($attributes_array AS $attribute_code=>$attribute_values){

            $increment ++;

            //  $attribute = $this->_options->getAttributes($attribute_code);
            //    print_r($attribute);
            //   exit;
            //  $attribute_id = $attribute->getId();
            //         echo $attribute_id;


            $attribute_values_exp = explode(',', $attribute_values);


            foreach($attribute_values_exp AS $index => $attribute_value){

                try{


                    //echo $attribute_code . "| ". $attribute_value;

                    //get the option id fr

                    if(empty($attribute_code) || empty($attribute_value)){
                        continue;
                    }


                    if($attribute_code == 'mattress_firmness'){
                        $optionArray['attribute_id'] = 291;
                        $optionArray['option_id'] = $attribute_value;

                     //   $optionArray = $this->_optionsHelper->getAttributeOptionId($attribute_code, $attribute_value);


                    }
                    else{
                        $optionArray = $this->_optionsHelper->getAttributeOptionId($attribute_code, $attribute_value);


                    }
                    //   print_r($optionArray);
                    //  echo "<br>TESTFILT123".$attribute_code . "| ". $attribute_value;


                    // print_r($options);
                }  catch(Exception $e){
                    continue;

                }

                $option_id = $attribute_value;
                $attribute_id = false;
                if(isset($optionArray['attribute_id'])){
                $attribute_id = $optionArray['attribute_id'];
                }




                //if(is_numeric($attribute_id) && is_numeric($option_id)){

                if(!empty($attribute_id) && !empty($option_id)){

                    // $attributes_array_numeric[$attribute_id] = $option_id;
                    $sqlStatement[] = "INNER JOIN elevate_landingpages_attributes B$increment on B$increment.landingpage_landingpage_id = A.landingpage_id";
                  //  $sqlStatement[] = "attribute_id = $attribute_id AND option_id = $option_id";
                   $sqlWhere[] = "B$increment.attribute_id = $attribute_id AND B$increment.option_id = '$option_id'";
                    //$sqlValues[] = $attribute_id;
                   // $sqlValues[] = $option_id; //$option_id;

                    $option_ids[] = $option_id;
                }

            }

        }



        $connection = $this->resourceConnection->getConnection();
        //$tableName = $resource->getTableName('mytest'); // the table name in this example is 'mytest'
        $category_ids = false;
        $category_lookup = '';
        //  AND A.category_ids = '$category_ids'
        if(count($sqlStatement) > 0){


            $link_category = $this->_registry->registry('current_category');//get current category

            if(isset($category_id)){
                $category_ids = $category_id;
               // echo "CAT1";

            } else if(is_numeric($category)){

             //   echo "CAT2";

                $link_category = $this->_category->load($category);

            }
            else{
             //   echo "NOCAT";
            }

            if ($link_category) {
                $category_ids = $link_category->getId();
            }
            if(isset($cat_override_id)){
                $category_ids = $cat_override_id;

            }

            //  NEED TO REIMPLEMENT STANDALONE
            ///  AND A.standalone != 1
            try{
                $param_count = count($sqlStatement);

                if($category_ids){
                    $category_lookup = "AND FIND_IN_SET('$category_ids', A.category_ids) ";
                }



                //  print_r($sqlValues);
/*
                $landing_id = $connection->fetchOne("SELECT * FROM
        elevate_landingpages A WHERE

       ".implode(' AND ', $sqlStatement)."

      $category_lookup
      AND A.standalone = 0

      AND ((select count(landingpage_id) FROM elevate_landingpages_attributes B WHERE B.landingpage_landingpage_id = A.landingpage_id ) = $param_count)
       limit 0, 1", $sqlValues

                );
*/


$query = "



SELECT * FROM elevate_landingpages A 

   ".implode(' ', $sqlStatement)." 
   
WHERE 
       
       ".implode(' AND ', $sqlWhere)." 
        
     
AND (A.standalone = 0 OR A.standalone IS NULL)
$category_lookup
         AND ((select count(landingpage_id) FROM elevate_landingpages_attributes B WHERE B.landingpage_landingpage_id = A.landingpage_id ) = $param_count)
       ";


                $landing_id = $connection->fetchAll($query);
unset($sqlStatement);
/*
        elevate_landingpages A

      $category_lookup
      AND A.standalone = 0

      AND ((select count(landingpage_id) FROM elevate_landingpages_attributes B WHERE B.landingpage_landingpage_id = A.landingpage_id ) = $param_count)
       limit 0, 1", $sqlValues

                );
*/
            //  echo $query;
            }
            catch(Exception $e){
                print_r($e->getMessage());
                exit;
            }



        }

        if(isset($landing_id[0])){



            $landing_id[0]['assigned_option_ids'] = $option_ids;
            return $landing_id[0];
        }

        return false;


        //  return $this;
    }
}