<?php

namespace Elevate\Themeoptions\Model;

use Elevate\Themeoptions\Api\Data\OptionsInterface;
use Elevate\Themeoptions\Api\Data\OptionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Options extends \Magento\Framework\Model\AbstractModel
{
    protected $headerbgcolor;
    protected $headergeneralcolor;

    protected $optionsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_themeoptions_options';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param OptionsInterfaceFactory $optionsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Themeoptions\Model\ResourceModel\Options $resource
     * @param \Elevate\Themeoptions\Model\ResourceModel\Options\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        OptionsInterfaceFactory $optionsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Themeoptions\Model\ResourceModel\Options $resource,
        \Elevate\Themeoptions\Model\ResourceModel\Options\Collection $resourceCollection,
        array $data = []
    ) {
        $this->optionsDataFactory = $optionsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve model with data
     * @return OptionsInterface
     */
    public function getDataModel()
    {
        $optionsData = $this->getData();

        $optionsDataObject = $this->optionsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $optionsDataObject,
            $optionsData,
            OptionsInterface::class
        );

        return $optionsDataObject;
    }
}
