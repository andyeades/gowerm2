<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Model;

use Elevate\Productdeepercontent\Api\Data\DeepercontentInterface;
use Elevate\Productdeepercontent\Api\Data\DeepercontentInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Deepercontent extends \Magento\Framework\Model\AbstractModel
{

    protected $deepercontentDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_productdeepercontent_deepercontent';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeepercontentInterfaceFactory $deepercontentDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent $resource
     * @param \Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeepercontentInterfaceFactory $deepercontentDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent $resource,
        \Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deepercontentDataFactory = $deepercontentDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deepercontent model with deepercontent data
     * @return DeepercontentInterface
     */
    public function getDataModel()
    {
        $deepercontentData = $this->getData();
        
        $deepercontentDataObject = $this->deepercontentDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deepercontentDataObject,
            $deepercontentData,
            DeepercontentInterface::class
        );
        
        return $deepercontentDataObject;
    }
}

