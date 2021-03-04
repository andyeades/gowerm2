<?php


namespace Elevate\Promotions\Model;

use Elevate\Promotions\Api\Data\PromotionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Elevate\Promotions\Api\Data\PromotionsInterface;

class Promotions extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $promotionsDataFactory;

    protected $_eventPrefix = 'elevate_promotions_promotions';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PromotionsInterfaceFactory $promotionsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Promotions\Model\ResourceModel\Promotions $resource
     * @param \Elevate\Promotions\Model\ResourceModel\Promotions\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PromotionsInterfaceFactory $promotionsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Promotions\Model\ResourceModel\Promotions $resource,
        \Elevate\Promotions\Model\ResourceModel\Promotions\Collection $resourceCollection,
        array $data = []
    ) {
        $this->promotionsDataFactory = $promotionsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve promotions model with promotions data
     * @return PromotionsInterface
     */
    public function getDataModel()
    {
        $promotionsData = $this->getData();
        
        $promotionsDataObject = $this->promotionsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $promotionsDataObject,
            $promotionsData,
            PromotionsInterface::class
        );
        
        return $promotionsDataObject;
    }
}
