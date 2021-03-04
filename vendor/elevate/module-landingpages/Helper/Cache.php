<?php
namespace Elevate\LandingPages\Helper;

use Magento\Framework\App\Helper;
use Magento\Store\Model\ScopeInterface; //required when  using engine interface
use \Magento\CatalogSearch\Model\ResourceModel\EngineInterface;


class Cache extends Helper\AbstractHelper
{
    const CACHE_TAG = 'ELEVATE_LANDINGPAGES';
    const CACHE_ID = 'elevate_landingpages';
    const CACHE_LIFETIME = 86400;

    protected $cache;
    protected $cacheState;
    protected $storeManager;
    private $storeId;
    protected $_optionsMapping = [];
    protected $_resource;
    protected $connection;
    private $eavConfig;
    protected $_strKeys = [];
    protected $_currentEngine = '';
    /**
     * Cache constructor.
     * @param Helper\Context $context
     * @param \Magento\Framework\App\Cache $cache
     * @param \Magento\Framework\App\Cache\State $cacheState
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Helper\Context $context,
        \Magento\Framework\App\Cache $cache,
        \Magento\Framework\App\Cache\State $cacheState,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->cache = $cache;
        $this->cacheState = $cacheState;
        $this->storeManager = $storeManager;
        $this->storeId = $storeManager->getStore()->getId();
        $this->_resource = $resource;
        $this->eavConfig = $eavConfig;


        parent::__construct($context);
    }
    protected function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->_resource->getConnection('core_write');
        }

        return $this->connection;
    }
    /**
     * @param $method
     * @param array $vars
     * @return string
     */
    public function getId($method, $vars = array())
    {
        return base64_encode($this->storeId . self::CACHE_ID . $method . implode('', $vars));
    }

    /**
     * @param $cacheId
     * @return bool|string
     */
    public function load($cacheId)
    {
        if ($this->cacheState->isEnabled(self::CACHE_ID)) {
            return json_decode($this->cache->load($cacheId), true);
        }

        return FALSE;
    }
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @param $data
     * @param $cacheId
     * @param int $cacheLifetime
     * @return bool
     */
    public function save($data, $cacheId, $cacheLifetime = self::CACHE_LIFETIME)
    {

        if ($this->cacheState->isEnabled(self::CACHE_ID)) {

            try{


            $this->cache->save(json_encode($data), $cacheId, array(self::CACHE_TAG), $cacheLifetime);



            }
            catch(Exception $e){



            }

            return TRUE;
        }
        return FALSE;
    }
    /**
     * @param $str
     *
     * @return string
     */
    public function formatKey($str) {
        if (isset($this->_strKeys[$str])) {
            return $this->_strKeys[$str];
        }

       // if ($this->shouldReplaceAllChars()) {
            $key = $str;
            $key = preg_replace('#[^0-9a-z]+#i', '-', $key);
            $key = strtolower($key);
            $key = trim($key, '-');
       // } else {
         //   $key = str_replace(',', '-', $str);
       // }

        $this->_strKeys[$str] = $key;

        return $key;
    }
    /**
     * @param string $attrCode
     *
     * @return array
     */
    public function getOptionsMapping($attrCode) {



        if (isset($this->_optionsMapping[$attrCode])) {
            return $this->_optionsMapping[$attrCode];
        }





        $cacheId = self::CACHE_TAG . $attrCode;
        if ( false !== ($data = $this->load($cacheId)) && 1 == 2) {
            $options = unserialize($data);
        } else {

            $connection = $this->getConnection();

            $attrTable = $this->_resource->getTableName('eav_attribute');
            $optionTable = $this->_resource->getTableName('eav_attribute_option');
            $optionValueTable = $this->_resource->getTableName('eav_attribute_option_value');

          //  $entityTypeId = Mage::getSingleton('eav/config')->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getId();
    $entityTypeId = $this->eavConfig->getEntityType(\Magento\Catalog\Model\Product::ENTITY)->getEntityTypeId();
            // Fetching all product attribute options
            $select = $connection->select();
            $select->from(array('o' => $optionTable), 'option_id')->join(array('a' => $attrTable), 'o.attribute_id = a.attribute_id', 'attribute_code')->join(array('v' => $optionValueTable), 'o.option_id = v.option_id', array(
                'store_id',
                'value'
            ))->where('a.entity_type_id = ?', $entityTypeId)->where('a.attribute_code = ?', $attrCode)->where('a.frontend_input IN(?)', array(
                'select',
                'multiselect'
            ));
            $rows = $connection->fetchAll($select);


            // Array that contains options mapping
            $options = array(
                'ids'  => array(),
                'keys' => array(),
            );

            // Filling values explicitly defined
            foreach ($rows as $row) {

                $storeId = $row['store_id'];
                $key = $this->formatKey($row['value']);
                $row['key'] = $key;
                $options['ids'][$storeId][$row['option_id']] = $row;
                $options['keys'][$storeId][$row['attribute_code']][$key][] = $row;
            }
            $admin_store_id = 0;
            if (isset($options['ids'][0])) {
                $the_ids = $options['ids'][$admin_store_id];
                // Filling empty values for stores that don't have a value defined
                if (is_array($the_ids)) {
                    foreach ($the_ids as $row) {
                        foreach ($this->storeManager->getStores() as $store) {
                            $storeId = $store->getId();
                            if (!isset($options['ids'][$storeId][$row['option_id']])) {
                                $key = $this->formatKey($row['value']);
                                $row['key'] = $key;
                                $options['ids'][$storeId][$row['option_id']] = $row;
                                $options['keys'][$storeId][$row['attribute_code']][strtolower(str_replace(' ', '-', $key))][] = $row;
                            }
                        }
                    }
                }
            }

            $this->save(serialize($options), $cacheId);
        }

        $this->_optionsMapping[$attrCode] = $options;

        return $options;
    }

    /**
     * @param string $attrCode
     * @param string $optionKey
     * @param null   $store
     *
     * @return string
     */
    public function getOptionId($attrCode, $optionKey, $storeId = false)
    {
        if (!$optionKey) {
           // echo "RETURN";
            return $optionKey;
        }
        $options = $this->getOptionsMapping($attrCode);
       //echo "<pre>";print_r($options);     echo "</pre>";
        if(!$storeId){

            $storeId = $this->storeManager->getStore()->getId();
        }
//echo "['keys'][$storeId][$attrCode][$optionKey][0]<br>";
        if (!isset($options['keys'][$storeId][$attrCode][$optionKey][0])) {
//echo "ret2$optionKey<br>";
            return $optionKey;
        }
        $optionId = $options['keys'][$storeId][$attrCode][$optionKey][0]['option_id'];

      //  echo "OPTION ID = $optionId";
        return $optionId;
    }

    /**
     * Check the engine provider is 'elasticsearch'
     *
     * @return bool
     */
    public function isElasticSearchEngine()
    {
        if (!$this->_currentEngine) {
            $this->_currentEngine = $this->scopeConfig->getValue(EngineInterface::CONFIG_ENGINE_PATH, ScopeInterface::SCOPE_STORE);
        }
        if($this->_currentEngine == 'elasticsearch' || $this->_currentEngine == 'elasticsearch5'
            || $this->_currentEngine == 'elasticsearch6'  || $this->_currentEngine == 'elasticsearch7' ) {
            return true;
        }

        return false;
    }

}