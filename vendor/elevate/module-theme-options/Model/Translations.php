<?php

namespace Elevate\Themeoptions\Model;

use Elevate\Themeoptions\Api\Data\TranslationsInterface;
use Elevate\Themeoptions\Api\Data\TranslationsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Translations extends \Magento\Framework\Model\AbstractModel
{
    protected $translationsDataFactory;
    protected $dataObjectHelper;
    protected $_eventPrefix = 'elevate_themeoptions_translations';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TranslationsInterfaceFactory $translationsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Themeoptions\Model\ResourceModel\Translations $resource
     * @param \Elevate\Themeoptions\Model\ResourceModel\Translations\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TranslationsInterfaceFactory $translationsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Themeoptions\Model\ResourceModel\Translations $resource,
        \Elevate\Themeoptions\Model\ResourceModel\Translations\Collection $resourceCollection,
        array $data = []
    ) {
        $this->translationsDataFactory = $translationsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve model with data
     * @return TranslationsInterface
     */
    public function getDataModel()
    {
        $translationsData = $this->getData();

        $translationsDataObject = $this->translationsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $translationsDataObject,
            $translationsData,
            TranslationsInterface::class
        );

        return $translationsDataObject;
    }
}
