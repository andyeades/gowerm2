<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Plugin\CustomerData;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\CustomerData\Customer;

/**
 * Class CustomerPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\CustomerData
 */
class CustomerPlugin
{
    /**
     * @var CurrentCustomer
     */
    private $currentCustomer;

    /**
     * @param CurrentCustomer $currentCustomer
     */
    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * @param Customer $subject
     * @param string[] $result
     * @return string[]
     */
    public function afterGetSectionData($subject, $result)
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return $result;
        }
        $customer = $this->currentCustomer->getCustomer();
        $result['email'] = $customer->getEmail();

        return $result;
    }
}
