<?php

namespace Firebear\ConfigurableProducts\Ui\DataProvider;

use Firebear\ConfigurableProducts\Model\ResourceModel\ProductOptions;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler;
use Magento\Framework\UrlInterface;

/**
 * Class UpdateMatrixYAxis
 * @package Firebear\ConfigurableProducts\Ui\DataProvider
 */
class UpdateMatrixYAxis extends UpdateMatrixXAxis
{
    /**
     * Get Data for Mass Update Y Axis Controller
     *
     * @return array
     */
    public function getActions()
    {
        return $this->getActionsData(self::MASS_UPDATE_Y_AXIS_CONTROLLER_NAME);
    }
}

