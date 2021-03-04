<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */


namespace Elevate\CartAssignments\Helper;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;


class Settings extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_coreSession;
    protected $_assetRepo;
    protected $_cartAssignments;
    protected $_ruleFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    protected $donationsEnable;

    public function __construct(

        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        DateTime $date,
        TimezoneInterface $timezone,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory,
        \Elevate\CartAssignments\Model\QuoteItemAssignmentsFactory $cartAssignments,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

    ) {

        $this->_coreSession = $coreSession;
            $this->_assetRepo = $assetRepo;
        $this->date = $date;
        $this->_cartAssignments = $cartAssignments;
        $this->timezone = $timezone;
        $this->_ruleFactory = $ruleFactory;
        $this->scopeConfig = $scopeConfig;
        $this->donationsEnable = $this->scopeConfig->getValue('elevate_cartassignments/general/donations_enable');
    }


    public function getPriceType(){


        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        $price_type = $this->scopeConfig->getValue('elevate_cartassignments/general/price_type', $storeScope);

        //should come from the config
        return $price_type;


    }

    public function getDonationsEnable() {
        return $this->donationsEnable;
}
}
