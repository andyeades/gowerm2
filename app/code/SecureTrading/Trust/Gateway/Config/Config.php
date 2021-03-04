<?php

namespace SecureTrading\Trust\Gateway\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Encryption\EncryptorInterface;

/**
 * Class Config
 *
 * @package SecureTrading\Trust\Gateway\Config
 */
class Config extends \Magento\Payment\Gateway\Config\Config
{
	/**
	 * @var \Magento\Framework\Module\FullModuleList
	 */
	private $fullModuleList;
    /**
     * @var EncryptorInterface
     */
    private $enc;

	/**
	 * Config constructor.
	 *
	 * @param ScopeConfigInterface $scopeConfig
	 * @param \Magento\Framework\Module\FullModuleList $fullModuleList
	 * @param EncryptorInterface $enc
	 * @param null $methodCode
	 * @param string $pathPattern
	 */
	public function __construct(
		ScopeConfigInterface $scopeConfig,
		\Magento\Framework\Module\FullModuleList $fullModuleList,
		EncryptorInterface $enc,
		$methodCode = null,
		$pathPattern = \Magento\Payment\Gateway\Config\Config::DEFAULT_PATH_PATTERN

	) {
		parent::__construct($scopeConfig, $methodCode, $pathPattern);
		$this->fullModuleList = $fullModuleList;
		$this->enc = $enc;
	}

	/**
	 * @var array
	 */
	private $_attribute = [
		'site_reference',
		'username',
		'password',
		'site_password'
	];
    /**
     * @var array
     */
	private $_secureAttribute = [
        'site_password',
        'password'
    ];

	/**
	 * @var array
	 */
	private $_endPoint = [
		'url_choice_page',
		'url_details_page'
	];

	/**
	 * @var array
	 */
	private $_secureField = [
		'sitereference',
		'currencyiso3a',
		'mainamount',
		'orderreference',
		'billingemail',
		'settleduedate',
		'settlestatus',
		'accounttypedescription',
		'isusediframe',
		'sitesecuritytimestamp',
		'password'
	];

	/**
	 * @param string $field
	 * @param null $storeId
	 * @return mixed
	 */
    public function getValue($field, $storeId = null)
    {
        if (in_array($field, $this->_attribute)) {
            if ((bool)parent::getValue('is_test'))
                return $this->_getValue($field, parent::getValue('test_' . $field));
        }
        return $this->_getValue($field, parent::getValue($field));
    }

    /**
     * @param $field
     * @param $value
     * @return string
     */
    private function _getValue($field, $value){
	    if (in_array($field, $this->_secureAttribute)){
	        return $this->enc->decrypt($value);
        }
	    if (in_array($field, $this->_endPoint)){
	    	return parent::getValue('endpoint').parent::getValue($field);
		}
	    return $value;
    }
	/**
	 * @return string
	 */
	public function getVersionInformation()
	{
		$moduleVersion = $this->fullModuleList->getOne('SecureTrading_Trust');
		$stppVersion   = isset($moduleVersion['setup_version']) ? $moduleVersion['setup_version'] : "";
		/** @var \Magento\Framework\App\ProductMetadataInterface $productMetadata */
		$productMetadata = ObjectManager::getInstance()->get(\Magento\Framework\App\ProductMetadataInterface::class);
		$edition         = $productMetadata->getEdition();
		$fullVersion     = $productMetadata->getVersion();
		$str             = sprintf('Magento %s %s (SecureTrading_Trust-%s)', $edition, $fullVersion, (string)$stppVersion);
		return $str;
	}

    /**
     * @param $params
     * @return string
     */
    public function getSiteSecurity($params)
	{
		$params['password'] = $this->getValue('site_password');
//		$params['password'] = '';
		$string             = '';
		foreach ($this->_secureField as $value) {
			if(isset($params[$value]))
				$string .= $params[$value];
		}
		$hash = hash("sha256", $string);
		return 'h'.$hash;
	}
}
