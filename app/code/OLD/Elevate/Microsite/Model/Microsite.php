<?php


namespace Elevate\Microsite\Model;

use Elevate\Microsite\Api\Data\MicrositeInterface;
use Elevate\Microsite\Api\Data\MicrositeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Microsite extends \Magento\Framework\Model\AbstractModel
{

    protected $micrositeDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_microsite_microsite';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param MicrositeInterfaceFactory $micrositeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Microsite\Model\ResourceModel\Microsite $resource
     * @param \Elevate\Microsite\Model\ResourceModel\Microsite\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        MicrositeInterfaceFactory $micrositeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Microsite\Model\ResourceModel\Microsite $resource,
        \Elevate\Microsite\Model\ResourceModel\Microsite\Collection $resourceCollection,
        array $data = []
    ) {
        $this->micrositeDataFactory = $micrositeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve microsite model with microsite data
     * @return MicrositeInterface
     */
    public function getDataModel()
    {
        $micrositeData = $this->getData();
        
        $micrositeDataObject = $this->micrositeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $micrositeDataObject,
            $micrositeData,
            MicrositeInterface::class
        );
        
        return $micrositeDataObject;
    }
}
