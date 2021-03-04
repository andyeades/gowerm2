<?php

namespace Firebear\ConfigurableProducts\Ui\Component\Listing\MassActions;

/**
 * Class ProductAttributesDynamicAction
 * @package Firebear\ConfigurableProducts\Ui\Component\Listing\MassActions
 */
class ProductAttributesDynamicAction extends \Magento\Ui\Component\Action
{
    /**
     * @inheritDoc
     */
    public function prepare()
    {
        $config = $this->getData('config');
        if (isset($config['action_resource'])) {
            $this->actions = $config['action_resource']->getActions();

            parent::prepare();
        }
    }
}
