<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template;

use Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface;
use Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface;
use Magento\Catalog\Model\Product;
use Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\Collection;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;

/**
 * Template option model
 */
class Option extends \Magento\Framework\Model\AbstractModel implements TemplateOptionInterface
{
    /**
     * @var \Aitoc\OptionsManagement\Model\Template\OptionRepository
     */
    protected $optionRepository;

    /**
     * @var \Aitoc\OptionsManagement\Model\Template\Option\ValueRepository
     */
    protected $optionValueRepository;

    /**
     * Option type percent
     */
    protected static $typePercent = 'percent';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $values = null;

    /**
     * Template option value
     *
     * @var Option\Value
     */
    protected $templateOptionValue;

    /**
     * Product option factory
     *
     * @var \Magento\Catalog\Model\Product\Option\Type\Factory
     */
    protected $optionTypeFactory;

    /**
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * @var Option\Validator\Pool
     */
    protected $validatorPool;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * Option constructor.
     * @param OptionRepository $optionRepository
     * @param Option\ValueRepository $optionValueRepository
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TemplateOptionValueInterface $templateOptionValue
     * @param Option\Type\Factory $optionFactory
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param Option\Validator\Pool $validatorPool
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Aitoc\OptionsManagement\Model\Template\OptionRepository $optionRepository,
        \Aitoc\OptionsManagement\Model\Template\Option\ValueRepository $optionValueRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TemplateOptionValueInterface $templateOptionValue,
        \Aitoc\OptionsManagement\Model\Template\Option\Type\Factory $optionFactory,
        \Magento\Framework\Stdlib\StringUtils $string,
        Option\Validator\Pool $validatorPool,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->optionRepository = $optionRepository;
        $this->optionValueRepository = $optionValueRepository;
        $this->templateOptionValue = $templateOptionValue;
        $this->optionTypeFactory = $optionFactory;
        $this->validatorPool = $validatorPool;
        $this->string = $string;
    }

    /**
     * Get resource instance
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _getResource()
    {
        return $this->_resource ?: parent::_getResource();
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Aitoc\OptionsManagement\Model\ResourceModel\Template\Option::class);
        parent::_construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionId()
    {
        return $this->getData(self::OPTION_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionId($id)
    {
        return $this->setData(self::OPTION_ID, $id);
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
    public function setTemplateId($optionId)
    {
        return $this->setData(self::TEMPLATE_ID, $optionId);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
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
    public function getIsRequire()
    {
        return $this->getData(self::IS_REQUIRE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsRequire($isRequired)
    {
        return $this->setData(self::IS_REQUIRE, $isRequired);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension()
    {
        return $this->getData(self::FILE_EXTENSION);
    }

    /**
     * {@inheritdoc}
     */
    public function setFileExtension($fileExtension)
    {
        return $this->setData(self::FILE_EXTENSION, $fileExtension);
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxCharacters()
    {
        return $this->getData(self::MAX_CHARACTERS);
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxCharacters($maxCharacters)
    {
        return $this->setData(self::MAX_CHARACTERS, $maxCharacters);
    }

    /**
     * {@inheritdoc}
     */
    public function getImageSizeX()
    {
        return $this->getData(self::IMAGE_SIZE_X);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageSizeX($imageSizeX)
    {
        return $this->setData(self::IMAGE_SIZE_X, $imageSizeX);
    }

    /**
     * {@inheritdoc}
     */
    public function getImageSizeY()
    {
        return $this->getData(self::IMAGE_SIZE_Y);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageSizeY($imageSizeY)
    {
        return $this->setData(self::IMAGE_SIZE_Y, $imageSizeY);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceType()
    {
        return $this->getData(self::PRICE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPriceType($priceType)
    {
        return $this->setData(self::PRICE_TYPE, $priceType);
    }

    public function getOptionValueRepository()
    {
        return $this->optionValueRepository;
    }

    /**
     * Add value of option to values array
     *
     * @param Option\Value $value
     * @return $this
     */
    public function addValue(Option\Value $value)
    {
        $this->values[$value->getId()] = $value;
        return $this;
    }

    /**
     * Get value by given id
     *
     * @param int $valueId
     * @return Option\Value|null
     */
    public function getValueById($valueId)
    {
        if (isset($this->values[$valueId])) {
            return $this->values[$valueId];
        }

        return null;
    }

    /**
     * Whether or not the option type contains sub-values
     *
     * @param string $type
     * @return bool
     */
    public function hasValues($type = null)
    {
        return $this->getGroupByType($type) == self::OPTION_GROUP_SELECT;
    }

    /**
     * @return TemplateOptionValueInterface[]|null
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Retrieve value instance
     *
     * @return Option\Value
     */
    public function getValueInstance()
    {
        return $this->templateOptionValue;
    }

    /**
     * Add option for save it
     *
     * @param array $option
     * @return $this
     */
    public function addOption($option)
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options for array
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Set options to empty array
     *
     * @return $this
     */
    public function unsetOptions()
    {
        $this->options = [];
        return $this;
    }

    /**
     * Get group name of option by given option type
     *
     * @param string $type
     * @return string
     */
    public function getGroupByType($type = null)
    {
        if ($type === null) {
            $type = $this->getType();
        }
        $optionGroupsToTypes = [
            'field' => 'text',
            'area' => 'text',
            'file' => 'file',
            'drop_down' => 'select',
            'radio' => 'select',
            'checkbox' => 'select',
            'multiple' => 'select',
            'date' => 'date',
            'date_time' => 'date',
            'time' => 'date',
        ];

        return isset($optionGroupsToTypes[$type]) ? $optionGroupsToTypes[$type] : '';
    }

    /**
     * Group model factory
     *
     * @param string $type Option type
     * @return \Aitoc\OptionsManagement\Model\Template\Option\Type\DefaultType
     * @throws LocalizedException
     */
    public function groupFactory($type)
    {
        $group = $this->getGroupByType($type);
        if (!empty($group)) {
            return $this->optionTypeFactory->create(
                'Aitoc\OptionsManagement\Model\Template\Option\Type\\' . $this->string->upperCaseWords($group)
            );
        }
        throw new LocalizedException(__('The option type to get group instance is incorrect.'));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function beforeSave()
    {
        parent::beforeSave();
        if ($this->getData('previous_type') != '') {
            $previousType = $this->getData('previous_type');

            /**
             * if previous option has different group from one is came now
             * need to remove all data of previous group
             */
            if ($this->getGroupByType($previousType) != $this->getGroupByType($this->getData('type'))) {
                switch ($this->getGroupByType($previousType)) {
                    case 'select':
                        $this->unsetData('values');
                        if ($this->getId()) {
                            $this->getValueInstance()->deleteValue($this->getId());
                        }
                        break;
                    case 'file':
                        $this->setData('file_extension', '');
                        $this->setData('image_size_x', '0');
                        $this->setData('image_size_y', '0');
                        break;
                    case 'text':
                        $this->setData('max_characters', '0');
                        break;
                    case 'date':
                        break;
                }
                if ($this->getGroupByType($this->getData('type')) == 'select') {
                    $this->setData('sku', '');
                    $this->unsetData('price');
                    $this->unsetData('price_type');
                    if ($this->getId()) {
                        $this->deletePrices($this->getId());
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Delete prices of option
     *
     * @param int $optionId
     * @return $this
     */
    public function deletePrices($optionId)
    {
        $this->getResource()->deletePrices($optionId);
        return $this;
    }

    /**
     * Delete titles of option
     *
     * @param int $optionId
     * @return $this
     */
    public function deleteTitles($optionId)
    {
        $this->getResource()->deleteTitles($optionId);
        return $this;
    }

    /**
     * Get Template Option Collection
     *
     * @param int $templateId
     * @param int $storeId
     * @return \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Collection
     */
    public function getTemplateOptions($templateId, $storeId = 0)
    {
        return $this->optionRepository->getTemplateOptions($templateId, $storeId);
    }

    /**
     * Get collection of values for current option
     *
     * @return Collection
     */
    public function getValuesCollection()
    {
        $collection = $this->getValueInstance()->getValuesCollection($this);

        return $collection;
    }

    /**
     * Get collection of values by given option ids
     *
     * @param array $optionIds
     * @param int $storeId
     * @return Collection
     */
    public function getOptionValuesByOptionId($optionIds, $storeId)
    {
        $collection = $this->templateOptionValue->getValuesByOption($optionIds, $this->getId(), $storeId);

        return $collection;
    }

    /**
     * Retrieve option searchable data
     *
     * @param int $templateId
     * @param int $storeId
     * @return array
     */
    public function getSearchableData($templateId, $storeId)
    {
        return $this->_getResource()->getSearchableData($templateId, $storeId);
    }

    /**
     * Clearing object's data
     *
     * @return $this
     */
    protected function _clearData()
    {
        $this->_data = [];
        $this->values = null;
        return $this;
    }

    /**
     * Clearing cyclic references
     *
     * @return $this
     */
    protected function _clearReferences()
    {
        if (!empty($this->values)) {
            foreach ($this->values as $value) {
                $value->unsetOption();
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getValidationRulesBeforeSave()
    {
        return $this->validatorPool->get($this->getType());
    }

    /**
     * @param TemplateOptionValueInterface[] $values
     * @return $this
     */
    public function setValues(array $values = null)
    {
        $this->values = $values;
        return $this;
    }
}
