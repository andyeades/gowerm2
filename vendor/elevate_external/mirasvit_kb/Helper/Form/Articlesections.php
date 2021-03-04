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



namespace Mirasvit\Kb\Helper\Form;

class Articlesections extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $systemStore;

    /**
     * Article constructor.
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->systemStore = $systemStore;

        parent::__construct($context);
    }

    /**
     * @param array $availableStores
     * @return array
     */
    public function formatStores($availableStores)
    {
        $allStores = $this->systemStore->getStoreValuesForForm(false, false);
        foreach ($allStores as $k => $store) {
            if (is_array($store['value'])) {
                foreach ($store['value'] as $key => $values) {
                    if (!in_array($values['value'], $availableStores)) {
                        unset($allStores[$k]['value'][$key]);
                    }
                }
                if (!count($allStores[$k]['value'])) {
                    unset($allStores[$k]);
                }
            } else {
                if (!in_array($store['value'], $availableStores)) {
                    unset($allStores[$k]);
                }
            }
        }

        return $allStores;
    }
}
