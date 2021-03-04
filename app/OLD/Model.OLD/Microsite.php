<?php


namespace Elevate\Microsite\Model;

use Elevate\Microsite\Api\Data\ItemsInterface;
use Elevate\Microsite\Api\Data\ItemsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Items extends \Magento\Framework\Model\AbstractModel
{

    protected $itemsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_microsite_microsite';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ItemsInterfaceFactory $itemsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Microsite\Model\ResourceModel\Microsite $resource
     * @param \Elevate\Microsite\Model\ResourceModel\Microsite\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ItemsInterfaceFactory $itemsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Microsite\Model\ResourceModel\Microsite $resource,
        \Elevate\Microsite\Model\ResourceModel\Microsite\Collection $resourceCollection,
        array $data = []
    ) {
        $this->itemsDataFactory = $itemsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve items model with items data
     * @return ItemsInterface
     */
    public function getDataModel()
    {
        $itemsData = $this->getData();
        
        $itemsDataObject = $this->itemsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $itemsDataObject,
            $itemsData,
            ItemsInterface::class
        );
        
        return $itemsDataObject;
    }
}
