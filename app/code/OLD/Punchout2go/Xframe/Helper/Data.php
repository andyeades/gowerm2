<?php
namespace Punchout2go\Xframe\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Event\Manager as EventManager;
use Punchout2go\Xframe\Logger\Handler\Debug;
use Punchout2go\Punchout\Model\Session as PUNSession;


/**
 * Adminhtml Catalog helper
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Data extends AbstractHelper
{
    /** @var \Punchout2go\Xframe\Logger\Handler\Debug */
    protected $logger;

    protected $moduleListInterface;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /**
     * @var \Punchout2go\Punchout\Model\Session
     */
    protected $punchoutSession;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context      $context
     * @param \Punchout2go\Punchout\Logger\Handler\Debug $punchout2go_logger
     * @param \Magento\Framework\Event\Manager           $eventManager
     */
    public function __construct(
        Context $context,
        Debug $logger,
        EventManager $eventManager,
        PUNSession $punchoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\ModuleListInterface $moduleListInterface
    ) {
        $this->punchoutSession = $punchoutSession;
        $this->storeManager = $storeManager;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
        $this->moduleListInterface = $moduleListInterface;
        parent::__construct($context);
        //if ($this->getConfig('punchout2go_xframe/system/log_to_punchout')) {
        //    $this->logger->log_to_punchout = 1;
        //}
    }

    /**
     *
     */
    public function isEnabled ()
    {
        // is module enabled
        if ($this->getConfigFlag('punchout2go_xframe/system/enabled')) {
            $this->extendedDebug('xframe enabled.'); // xframe is enabled.
            // is flagged for punchout only
            if ($this->getConfigFlag('punchout2go_xframe/system/punchout_only')
                && !$this->punchoutSession->isPunchoutSession()
                && !$this->isPunchoutControllerAction()) {
                $this->extendedDebug('punchout-only - non-session');
                return false; // punchout-only, but not a punchout
            }
            $this->debug('xframe is enabled');
            return true;
        }
        return false;
    }

    /**
     * test to see if we are in a punchout controller
     *
     * @return bool
     */
    public function isPunchoutControllerAction ()
    {
        $request = $this->_getRequest();
        $module_name = $request->getModuleName();
        $this->extendedDebug('module name '. $module_name); // xframe is enabled.
        if ($module_name == 'punchout') return true;
        return false;
    }

    /**
     * get the header
     *
     * @return string
     */
    public function getHeaderValue ()
    {
        if ($this->getConfigFlag('punchout2go_xframe/store/off')) {
            $this->debug('turn off x-frame');
            return 0; // turn off
        }
        if ($this->getConfigFlag('punchout2go_xframe/advanced/enabled')) {
            $this->debug('advanced use');
            return $this->getAdvancedHeaderValue();
        } else {
            $this->debug('use value');
            return $this->getConfig('punchout2go_xframe/store/header');
        }
    }

    /**
     * get the header value from advanced configurations
     *
     * @return string
     */
    public function getAdvancedHeaderValue()
    {
        $user_attribute = $this->getConfig('punchout2go_xframe/advanced/user_attribute');
        if (!empty($user_attribute)) {
            $customer = $this->punchoutSession->getCustomerSession()->getCustomer();
            if (!empty($customer)) {
                $user_value = $customer->getData($user_attribute);
            } else {
                $user_value = '_no_customer';
            }
            $this->debug('user '. $user_attribute .' => '. $user_value);
            $advanced_config = $this->getConfig('punchout2go_xframe/advanced/header_advanced');
            $headers = json_decode($advanced_config,true);
            if (!empty($headers)
                && is_array($headers)) {
                $this->debug('matching data',$headers);
                $default = false;
                foreach ($headers AS $option) {
                    if (is_array($option)
                        && isset($option['user_value'])
                        && isset($option['header'])) {
                        if ($option['user_value'] == $user_value) {
                            return $option['header'];
                        } elseif ($option['user_value'] = '*') {
                            $default = $option['header'];
                        }
                    }
                }
                if ($default
                    && $user_value != '_no_customer') {
                    $this->debug('no matches found, use advanced default '. $default);
                    return $default;
                }
                $this->debug('no matches found');
            } else {
                $this->debug('no values found');
            }
            $this->debug('use module default');
            return $this->getConfig('punchout2go_xframe/store/header');
        }
        $this->debug('advanced misconfigured, use application default');
        return;
    }

    /**
     * log data.
     *
     * @param string $string the string you want to log.
     * @param array  $context
     *
     * @internal param bool $force force the logging regardless of setting.
     */
    public function debug($string, array $context = array())
    {
        if ($this->getConfigFlag('punchout2go_xframe/system/logging')) {
            $this->logger->simple_log($string, $context);
        }
    }

    public function extendedDebug ($string, array $context = array())
    {
        if ($this->getConfigFlag('punchout2go_xframe/system/extended_logging')) {
            $this->debug($string, $context);
        }
    }

    /**
     * Grabs version number for display in configuration for Purchase Order.
     *
     * @return string
     */
    public function getModuleVersion()
    {
        $data = $this->moduleListInterface->getOne('Punchout2go_Xframe');
        if (isset($data['setup_version'])) {
            $version = $data['setup_version'];
        } else {
            $version = "";
        }
        return $version;
    }

    /**
     * get a magento formed URL
     *
     * @param       $url
     * @param array $params
     *
     * @return string
     */
    public function getUrl($url, $params = array())
    {
        return $this->_getUrl($url, $params);
    }

    /**
     * get the config value.
     *
     * @param $config_path
     *
     * @return mixed
     */
    public function getConfig($config_path)
    {
        $store = $this->storeManager->getStore()->getId();
        return $this->scopeConfig->getValue($config_path,'store',$store);
    }

    /**
     * get the config flag (ie boolean)
     *
     * @param $config_path
     *
     * @return bool
     */
    public function getConfigFlag($config_path)
    {
        $store = $this->storeManager->getStore()->getId();
        return (boolean) $this->scopeConfig->getValue($config_path,'store',$store);
    }

}
