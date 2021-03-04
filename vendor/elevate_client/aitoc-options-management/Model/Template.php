<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model;

use Aitoc\OptionsManagement\Api\Data\TemplateInterface;

/**
 * Class Template
 * @package Magento\Customer\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Template extends \Magento\Framework\Model\AbstractModel implements TemplateInterface
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * @var \Aitoc\OptionsManagement\Model\TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var \Aitoc\OptionsManagement\Model\Template\OptionRepository
     */
    protected $optionRepository;

    /**
     * @var array|null
     */
    protected $options = null;

    /**
     * @param \Aitoc\OptionsManagement\Model\TemplateRepository $templateRepository
     * @param \Aitoc\OptionsManagement\Model\Template\OptionRepository $optionRepository
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @param \Aitoc\OptionsManagement\Model\Template\OptionRepository $optionRepository
     */
    public function __construct(
        \Aitoc\OptionsManagement\Model\TemplateRepository $templateRepository,
        \Aitoc\OptionsManagement\Model\Template\OptionRepository $optionRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->templateRepository = $templateRepository;
        $this->optionRepository = $optionRepository;
    }

    /**
     * Object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Aitoc\OptionsManagement\Model\ResourceModel\Template');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateId()
    {
        return $this->getData(self::TEMPLATE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplateId($id)
    {
        return $this->setData(self::TEMPLATE_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsReplaceProductSku()
    {
        return $this->getData(self::IS_REPLACE_PRODUCT_SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsReplaceProductSku($flag)
    {
        return $this->setData(self::IS_REPLACE_PRODUCT_SKU, $flag);
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdateddAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdateddAt($updateddAt)
    {
        return $this->setData(self::UPDATED_AT, $updateddAt);
    }

    /**
     * Get all options of template
     *
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface[]|null
     */
    public function getOptions()
    {
        if (is_null($this->options)) {
            $this->options = $this->optionRepository->getTemplateOptions($this->getTemplateId(), $this->getStoreId());
            foreach($this->options as $option) {
                $option->setTemplate($this)->setStoreId($this->getStoreId());
                $values = $option->getValues();
                if ($values) {
                    foreach($values as $value) {
                        $value->setStoreId($this->getStoreId());
                    }
                }
            }
        }
        return $this->options;
    }

    /**
     * @return $this
     */
    public function resetOptions()
    {
        $this->options = null;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return \Aitoc\OptionsManagement\Model\TemplateRepository
     */
    public function getTemplateRepository()
    {
        return $this->templateRepository;
    }

    /**
     * @return \Aitoc\OptionsManagement\Model\Template\OptionRepository
     */
    public function getOptionRepository()
    {
        return $this->optionRepository;
    }

    /**
     * Retrieve array of product id's for template
     *
     * @return array
     */
    public function getProducts()
    {
        if (!$this->getId()) {
            return [];
        }

        $array = $this->getData('products');
        if ($array === null) {
            $array = $this->getResource()->getProducts($this);
            $this->setData('products', $array);
        }
        return $array;
    }
}
