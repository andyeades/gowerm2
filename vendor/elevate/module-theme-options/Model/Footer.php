<?php

namespace Elevate\Themeoptions\Model;

use Elevate\Themeoptions\Api\Data\FooterInterface;
use Elevate\Themeoptions\Api\Data\FooterInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Footer extends \Magento\Framework\Model\AbstractModel
{
    protected $footerDataFactory;
    protected $dataObjectHelper;
    protected $_eventPrefix = 'elevate_themeoptions_footer';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param FooterInterfaceFactory $footerDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Themeoptions\Model\ResourceModel\Footer $resource
     * @param \Elevate\Themeoptions\Model\ResourceModel\Footer\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        FooterInterfaceFactory $footerDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Themeoptions\Model\ResourceModel\Footer $resource,
        \Elevate\Themeoptions\Model\ResourceModel\Footer\Collection $resourceCollection,
        array $data = []
    ) {
        $this->footerDataFactory = $footerDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve model with data
     * @return FooterInterface
     */
    public function getDataModel()
    {
        $footerData = $this->getData();

        $footerDataObject = $this->footerDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $footerDataObject,
            $footerData,
            FooterInterface::class
        );

        return $footerDataObject;
    }
}
