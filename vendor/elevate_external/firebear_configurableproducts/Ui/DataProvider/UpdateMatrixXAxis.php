<?php

namespace Firebear\ConfigurableProducts\Ui\DataProvider;

use Firebear\ConfigurableProducts\Model\ResourceModel\ProductOptions;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler;
use Magento\Framework\UrlInterface;

/**
 * Class UpdateMatrixXAxis
 * @package Firebear\ConfigurableProducts\Ui\DataProvider
 */
class UpdateMatrixXAxis
{
    /**
     * @var ProductOptions
     */
    protected $productOptionsCollections;

    /**
     * @var ConfigurableAttributeHandler
     */
    protected $configurableAttributeHandler;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    const MASS_UPDATE_X_AXIS_CONTROLLER_NAME = 'massUpdateMatrixXAxis';
    const MASS_UPDATE_Y_AXIS_CONTROLLER_NAME = 'massUpdateMatrixYAxis';

    /**
     * UpdateMatrixXAxis constructor.
     * @param ProductOptions $productOptionsCollections
     * @param ConfigurableAttributeHandler $configurableAttributeHandler
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ProductOptions $productOptionsCollections,
        ConfigurableAttributeHandler $configurableAttributeHandler,
        UrlInterface $urlBuilder
    ) {
        $this->productOptionsCollections = $productOptionsCollections;
        $this->urlBuilder = $urlBuilder;
        $this->configurableAttributeHandler = $configurableAttributeHandler;
    }

    /**
     * Get Data for Mass Update X Axis Controller
     *
     * @return array
     */
    public function getActions()
    {
        return $this->getActionsData(self::MASS_UPDATE_X_AXIS_CONTROLLER_NAME);
    }

    /**
     * Get Data for Controller
     *
     * @param $controllerName
     * @return array
     */
    public function getActionsData($controllerName)
    {
        $actions = [];
        $actions[] =
            ['type' => 'default',
                'label' => 'Use extension settings',
                'url' => $this->urlBuilder->getUrl('icp/index/' . $controllerName, ['attribute_code' => 'default'])
            ];
        foreach ($this->configurableAttributeHandler->getApplicableAttributes() as $attributes) {
            if ($this->configurableAttributeHandler->isAttributeApplicable($attributes)) {
                $actions[] = [
                    'type' => $attributes->getAttributeCode(),
                    'label' => $attributes->getAttributeCode(),
                    'url' => $this->urlBuilder->getUrl(
                        'icp/index/' . $controllerName,
                        ['attribute_code' => $attributes->getAttributeCode()]
                    )
                ];
            }
        }
        return $actions;
    }
}
