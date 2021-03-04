<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Kb\Observer\Frontend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Module\Manager;

class CommentSaveObserver implements ObserverInterface
{
    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Manager              $moduleManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Manager $moduleManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->moduleManager = $moduleManager;
        $this->scopeConfig   = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        if ($this->moduleManager->isEnabled('MSP_ReCaptcha') &&
            $this->scopeConfig->getValue('msp_securitysuite_recaptcha/frontend/enabled')
        ) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $contactFormObserver = $objectManager->get('Mirasvit\Kb\Observer\Frontend\LocalCommentsObserver');
            $contactFormObserver->execute($observer);
        }
    }
}