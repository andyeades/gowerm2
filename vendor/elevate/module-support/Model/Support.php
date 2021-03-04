<?php


namespace Elevate\Support\Model;

use Elevate\Support\Api\Data\SupportInterface;
use Elevate\Support\Api\Data\SupportInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Support extends \Magento\Framework\Model\AbstractModel
{

    protected $supportDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_support_support';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param SupportInterfaceFactory $supportDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Support\Model\ResourceModel\Support $resource
     * @param \Elevate\Support\Model\ResourceModel\Support\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        SupportInterfaceFactory $supportDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Support\Model\ResourceModel\Support $resource,
        \Elevate\Support\Model\ResourceModel\Support\Collection $resourceCollection,
        array $data = []
    ) {
        $this->supportDataFactory = $supportDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve support model with support data
     * @return SupportInterface
     */
    public function getDataModel()
    {
        $supportData = $this->getData();
        
        $supportDataObject = $this->supportDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $supportDataObject,
            $supportData,
            SupportInterface::class
        );
        
        return $supportDataObject;
    }
}
