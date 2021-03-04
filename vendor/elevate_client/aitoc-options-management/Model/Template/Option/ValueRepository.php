<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template\Option;

use Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface;
use Aitoc\OptionsManagement\Model\Template\Option\ValueFactory;
use Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value as ResourceValue;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\ObjectManager;

class ValueRepository implements \Aitoc\OptionsManagement\Api\TemplateOptionValueRepositoryInterface
{
    /**
     * @var ValueFactory
     */
    protected $valueFactory;

    /**
     * @var ResourceValue
     */
    protected $valueResource;

    /**
     * @var \Aitoc\OptionsManagement\Model\TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var Converter
     */
    protected $converter;

    /**
     * Constructor
     *
     * @param ValueFactory|null $valueFactory
     * @param ResourceValue $valueResource
     * @param \Aitoc\OptionsManagement\Model\TemplateRepository $templateRepository
     * @param \Magento\Catalog\Model\Product\Option\Converter $converter
     */
    public function __construct(
        ValueFactory $valueFactory = null,
        ResourceValue $valueResource,
        \Aitoc\OptionsManagement\Model\TemplateRepository $templateRepository,
        \Magento\Catalog\Model\Product\Option\Converter $converter
    ) {
        $this->valueFactory = $valueFactory ?: ObjectManager::getInstance()->get(ValueFactory::class);
        $this->valueResource = $valueResource;
        $this->templateRepository = $templateRepository;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($valueId)
    {
        $value = $this->valueFactory->create();
        $this->valueFactory->load($value, $valueId);
        if (!$value->getId()) {
            throw new NoSuchEntityException(__('Template option value with id "%1" does not exist.', $valueId));
        }
        return $value;
    }

    /**
     * Retrieve empty option value.
     *
     * @return TemplateOptionValueInterface
     */
    public function getEmpty()
    {
        return $this->valueFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TemplateOptionValueInterface $value)
    {
        try {
            $this->valueResource->delete($value);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($valueId)
    {
        return $this->delete($this->getById($valueId));
    }

    /**
     * {@inheritdoc}
     */
    public function save(TemplateOptionValueInterface $value)
    {
        try {
            $this->valueResource->save($value);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save template option %1', $value->getId()));
        }
        return $value;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option\Value $productOptionValue
     * @return int
     * @throws CouldNotSaveException
     */
    public function saveRelation(\Magento\Catalog\Model\Product\Option\Value $productOptionValue)
    {
        try {
            return $this->valueResource->saveRelation($productOptionValue);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save option value relation ID %1', $productOptionValue->getId()));
        }
    }
}
