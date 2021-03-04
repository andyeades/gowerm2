<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\ResourceModel\Template;

use Aitoc\OptionsManagement\Api\Data\TemplateInterface;

/**
 * Catalog template custom option resource model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Option extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Currency factory
     *
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * Core config model
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_config;

    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Aitoc\OptionsManagement\Helper\Data $helper,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_currencyFactory = $currencyFactory;
        $this->_storeManager = $storeManager;
        $this->_config = $config;
        $this->helper = $helper;
    }

    /**
     * Define main table and initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('aitoc_optionsmanagement_template_option', 'option_id');
    }

    /**
     * Save options store data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveValuePrices($object);
        $this->_saveValueTitles($object);

        if ($this->helper->isDefaultValueEnabled()) {
            $this->_saveDefaultText($object);
        }


        if ($this->helper->isEnabledPerOptionEnabled()) {
            $this->_saveIsEnable($object);
        }

        $this->_saveValues($object);

        return parent::_afterSave($object);
    }

    /**
     * Save value prices
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _saveValuePrices(\Magento\Framework\Model\AbstractModel $object)
    {
        $priceTable = $this->getTable('aitoc_optionsmanagement_template_option_price');
        $connection = $this->getConnection();

        /*
         * Better to check param 'price' and 'price_type' for saving.
         * If there is not price skip saving price
         */

        if (in_array($object->getType(), $this->getPriceTypes())) {

            //save for store_id = 0
            if (!$object->getData('scope', 'price')) {
                $statement = $connection->select()->from(
                    $priceTable,
                    'option_id'
                )->where(
                    'option_id = ?',
                    $object->getId()
                )->where(
                    'store_id = ?',
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );
                $optionId = $connection->fetchOne($statement);

                if ($optionId) {
                    $data = $this->_prepareDataForTable(
                        new \Magento\Framework\DataObject(
                            ['price' => $object->getPrice(), 'price_type' => $object->getPriceType()]
                        ),
                        $priceTable
                    );

                    $connection->update(
                        $priceTable,
                        $data,
                        [
                            'option_id = ?' => $object->getId(),
                            'store_id  = ?' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                        ]
                    );
                } else {
                    $data = $this->_prepareDataForTable(
                        new \Magento\Framework\DataObject(
                            [
                                'option_id' => $object->getId(),
                                'store_id' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                                'price' => $object->getPrice(),
                                'price_type' => $object->getPriceType(),
                            ]
                        ),
                        $priceTable
                    );
                    $connection->insert($priceTable, $data);
                }
            }

            $scope = (int)$this->_config->getValue(
                \Magento\Store\Model\Store::XML_PATH_PRICE_SCOPE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            if ($object->getStoreId() != '0' && $scope == \Magento\Store\Model\Store::PRICE_SCOPE_WEBSITE) {
                $baseCurrency = $this->_config->getValue(
                    \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                    'default'
                );

                $storeIds = $this->_storeManager->getStore($object->getStoreId())->getWebsite()->getStoreIds();
                if (is_array($storeIds)) {
                    foreach ($storeIds as $storeId) {
                        if ($object->getPriceType() == 'fixed') {
                            $storeCurrency = $this->_storeManager->getStore($storeId)->getBaseCurrencyCode();
                            $rate = $this->_currencyFactory->create()->load($baseCurrency)->getRate($storeCurrency);
                            if (!$rate) {
                                $rate = 1;
                            }
                            $newPrice = $object->getPrice() * $rate;
                        } else {
                            $newPrice = $object->getPrice();
                        }

                        $statement = $connection->select()->from(
                            $priceTable
                        )->where(
                            'option_id = ?',
                            $object->getId()
                        )->where(
                            'store_id  = ?',
                            $storeId
                        );

                        if ($connection->fetchOne($statement)) {
                            $data = $this->_prepareDataForTable(
                                new \Magento\Framework\DataObject(
                                    ['price' => $newPrice, 'price_type' => $object->getPriceType()]
                                ),
                                $priceTable
                            );

                            $connection->update(
                                $priceTable,
                                $data,
                                ['option_id = ?' => $object->getId(), 'store_id  = ?' => $storeId]
                            );
                        } else {
                            $data = $this->_prepareDataForTable(
                                new \Magento\Framework\DataObject(
                                    [
                                        'option_id' => $object->getId(),
                                        'store_id' => $storeId,
                                        'price' => $newPrice,
                                        'price_type' => $object->getPriceType(),
                                    ]
                                ),
                                $priceTable
                            );
                            $connection->insert($priceTable, $data);
                        }
                    }
                }
            } elseif ($scope == \Magento\Store\Model\Store::PRICE_SCOPE_WEBSITE && $object->getData('scope', 'price')) {
                $connection->delete(
                    $priceTable,
                    ['option_id = ?' => $object->getId(), 'store_id  = ?' => $object->getStoreId()]
                );
            }
        } elseif ($object->getGroupByType($object->getType()) == 'select') {
            $connection->delete(
                $priceTable,
                ['option_id = ?' => $object->getId(), 'store_id  = ?' => $object->getStoreId()]
            );
        }

        return $this;
    }

    /**
     * Save titles
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _saveValueTitles(\Magento\Framework\Model\AbstractModel $object)
    {
        $connection = $this->getConnection();
        $titleTableName = $this->getTable('aitoc_optionsmanagement_template_option_title');
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $existInCurrentStore = $this->getColFromOptionTable($titleTableName, (int)$object->getId(), (int)$storeId);
            $existInDefaultStore = (int)$storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID ?
                $existInCurrentStore :
                $this->getColFromOptionTable(
                    $titleTableName,
                    (int)$object->getId(),
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );

            if ($object->getTitle()) {
                $isDeleteStoreTitle = (bool)$object->getData('is_delete_store_title');
                if ($existInCurrentStore) {
                    if ($isDeleteStoreTitle && (int)$storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
                        $connection->delete($titleTableName, ['option_title_id = ?' => $existInCurrentStore]);
                    } elseif ($object->getStoreId() == $storeId) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(['title' => $object->getTitle()]),
                            $titleTableName
                        );
                        $connection->update(
                            $titleTableName,
                            $data,
                            [
                                'option_id = ?' => $object->getId(),
                                'store_id  = ?' => $storeId,
                            ]
                        );
                    }
                } else {
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore) ||
                        (
                            $storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID &&
                            !$existInCurrentStore &&
                            !$isDeleteStoreTitle
                        )
                    ) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(
                                [
                                    'option_id' => $object->getId(),
                                    'store_id' => $storeId,
                                    'title' => $object->getTitle(),
                                ]
                            ),
                            $titleTableName
                        );
                        $connection->insert($titleTableName, $data);
                    }
                }
            } else {

                if ($object->getId() && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && $storeId
                ) {
                    $connection->delete(
                        $titleTableName,
                        [
                            'option_id = ?' => $object->getId(),
                            'store_id  = ?' => $object->getStoreId(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Save Default Text
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _saveDefaultText(\Magento\Framework\Model\AbstractModel $object)
    {
        $connection = $this->getConnection();
        $defaultTableName = $this->getTable('aitoc_optionsmanagement_template_option_default');
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $existInCurrentStore = $this->getColFromOptionTable($defaultTableName, (int)$object->getId(), (int)$storeId);
            $existInDefaultStore = (int)$storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID ?
                $existInCurrentStore :
                $this->getColFromOptionTable(
                    $defaultTableName,
                    (int)$object->getId(),
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );

            if (!is_null($object->getDefaultText())) {
                $isDeleteStoreDefaultText = (bool)$object->getData('is_delete_store_default_text');
                if ($existInCurrentStore) {
                    if ($isDeleteStoreDefaultText && (int)$storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
                        $connection->delete($defaultTableName, ['option_default_id = ?' => $existInCurrentStore]);
                    } elseif ($object->getStoreId() == $storeId) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(['default_text' => $object->getDefaultText()]),
                            $defaultTableName
                        );
                        $connection->update(
                            $defaultTableName,
                            $data,
                            [
                                'option_id = ?' => $object->getId(),
                                'store_id  = ?' => $storeId,
                            ]
                        );
                    }
                } else {
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore) ||
                        (
                            $storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID &&
                            !$existInCurrentStore &&
                            !$isDeleteStoreDefaultText
                        )
                    ) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(
                                [
                                    'option_id' => $object->getId(),
                                    'store_id' => $storeId,
                                    'default_text' => $object->getDefaultText(),
                                ]
                            ),
                            $defaultTableName
                        );
                        $connection->insert($defaultTableName, $data);
                    }
                }
            } else {
                if ($object->getId() && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && $storeId
                ) {
                    $connection->delete(
                        $defaultTableName,
                        [
                            'option_id = ?' => $object->getId(),
                            'store_id  = ?' => $object->getStoreId(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Save Is Enable
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _saveIsEnable(\Magento\Framework\Model\AbstractModel $object)
    {
        $connection = $this->getConnection();
        $isEnableTableName = $this->getTable('aitoc_optionsmanagement_template_option_is_enable');
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $existInCurrentStore = $this->getColFromOptionTable($isEnableTableName, (int)$object->getId(), (int)$storeId);
            $existInDefaultStore = (int)$storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID ?
                $existInCurrentStore :
                $this->getColFromOptionTable(
                    $isEnableTableName,
                    (int)$object->getId(),
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );

            if (!is_null($object->getIsEnable())) {
                $isDeleteStoreIsEnable = (bool)$object->getData('is_delete_store_is_enable');
                if ($existInCurrentStore) {
                    if ($isDeleteStoreIsEnable && (int)$storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
                        $connection->delete($isEnableTableName, ['option_is_enable_id = ?' => $existInCurrentStore]);
                    } elseif ($object->getStoreId() == $storeId) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(['is_enable' => (int)$object->getIsEnable()]),
                            $isEnableTableName
                        );
                        $connection->update(
                            $isEnableTableName,
                            $data,
                            [
                                'option_id = ?' => $object->getId(),
                                'store_id  = ?' => $storeId,
                            ]
                        );
                    }
                } else {
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore) ||
                        (
                            $storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID &&
                            !$existInCurrentStore &&
                            !$isDeleteStoreIsEnable
                        )
                    ) {
                        $data = $this->_prepareDataForTable(
                            new \Magento\Framework\DataObject(
                                [
                                    'option_id' => $object->getId(),
                                    'store_id' => $storeId,
                                    'is_enable' => (int)$object->getIsEnable(),
                                ]
                            ),
                            $isEnableTableName
                        );
                        $connection->insert($isEnableTableName, $data);
                    }
                }
            } else {
                if ($object->getId() && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && $storeId
                ) {
                    $connection->delete(
                        $isEnableTableName,
                        [
                            'option_id = ?' => $object->getId(),
                            'store_id  = ?' => $object->getStoreId(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Save template option values
     *
     * @param \Aitoc\OptionsManagement\Model\Template\Option $option
     * @return $this
     * @throws LocalizedException
     * @throws \Aitoc\OptionsManagement\Model\Template\Option\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function _saveValues(\Aitoc\OptionsManagement\Model\Template\Option $option)
    {
        $values = $option->getData('values');

        if (is_array($values)) {
            if ($option->getGroupByType($option->getType()) != 'select') {
                $values = [];
            }

            $newValues = [];
            $optionValueRepository = $option->getOptionValueRepository();
            $origValues = $option->getValues();
            if (!is_array($origValues)) {
                $origValues = [];
            }

            foreach ($values as $value) {
                if ($value instanceof \Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface) {
                    $value = $value->getData();
                }

                if (isset($value['is_delete']) && $value['is_delete']) {
                    continue;
                } elseif (isset($value['option_type_id']) && $value['option_type_id'] > 0) {
                    // update option
                    foreach($origValues as $origKey => $origValue) {
                        if ($origValue->getId() == $value['option_type_id']) {
                            $origValue->addData($value);
                            $newValues[] = $origValue;
                            unset($origValues[$origKey]);
                            break;
                        }
                    }
                } else {
                    // add new option
                    $value['option_type_id'] = null;
                    $value['option_id'] = $option->getId();
                    $newValues[] = $optionValueRepository->getEmpty()->addData($value);
                }
            }

            // remove options
            foreach($origValues as $value) {
                $optionValueRepository->delete($value);
            }

            if (!$newValues) {
                return $this;
            }

            foreach($newValues as $value) {
                $optionValueRepository->save($value);
            }

        } elseif ($option->getGroupByType($option->getType()) == 'select') {
            throw new LocalizedException(__('Select type options required values rows.'));
        }

        return $this;
    }

    /**
     * Get first col from from first row for option table
     *
     * @param string $tableName
     * @param int $optionId
     * @param int $storeId
     * @return string
     */
    protected function getColFromOptionTable($tableName, $optionId, $storeId)
    {
        $connection = $this->getConnection();
        $statement = $connection->select()->from(
            $tableName
        )->where(
            'option_id = ?',
            $optionId
        )->where(
            'store_id  = ?',
            $storeId
        );

        return $connection->fetchOne($statement);
    }

    /**
     * Delete prices
     *
     * @param int $optionId
     * @return $this
     */
    public function deletePrices($optionId)
    {
        $this->getConnection()->delete(
            $this->getTable('aitoc_optionsmanagement_template_option_price'),
            ['option_id = ?' => $optionId]
        );

        return $this;
    }

    /**
     * Delete titles
     *
     * @param int $optionId
     * @return $this
     */
    public function deleteTitles($optionId)
    {
        $this->getConnection()->delete(
            $this->getTable('aitoc_optionsmanagement_template_option_title'),
            ['option_id = ?' => $optionId]
        );

        return $this;
    }

    /**
     * Retrieve option searchable data
     *
     * @param int $templateId
     * @param int $storeId
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getSearchableData($templateId, $storeId)
    {
        $searchData = [];
        $connection = $this->getConnection();
        $titleCheckSql = $connection->getCheckSql(
            'option_title_store.title IS NULL',
            'option_title_default.title',
            'option_title_store.title'
        );

        // retrieve options title
        $defaultOptionJoin = implode(
            ' AND ',
            [
                'option_title_default.option_id=template_option.option_id',
                $connection->quoteInto(
                    'option_title_default.store_id = ?',
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                )
            ]
        );

        $storeOptionJoin = implode(
            ' AND ',
            [
                'option_title_store.option_id=template_option.option_id',
                $connection->quoteInto('option_title_store.store_id = ?', (int)$storeId)
            ]
        );

        $select = $connection->select()->from(
            ['template_option' => $this->getMainTable()],
            null
        )->join(
            ['option_title_default' => $this->getTable('aitoc_optionsmanagement_template_option_title')],
            $defaultOptionJoin,
            []
        )->join(
            ['cpe' => $this->getTable('aitoc_optionsmanagement_template')],
            sprintf(
                'cpe.%s = template_option.template_id',
                $this->getMetadataPool()->getMetadata(TemplateInterface::class)->getLinkField()
            ),
            []
        )->joinLeft(
            ['option_title_store' => $this->getTable('aitoc_optionsmanagement_template_option_title')],
            $storeOptionJoin,
            ['title' => $titleCheckSql]
        )->where(
            'cpe.entity_id = ?',
            $templateId
        );

        if ($titles = $connection->fetchCol($select)) {
            $searchData = array_merge($searchData, $titles);
        }

        //select option type titles
        $defaultOptionJoin = implode(
            ' AND ',
            [
                'option_title_default.option_type_id=option_type.option_type_id',
                $connection->quoteInto(
                    'option_title_default.store_id = ?',
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                )
            ]
        );

        $storeOptionJoin = implode(
            ' AND ',
            [
                'option_title_store.option_type_id = option_type.option_type_id',
                $connection->quoteInto('option_title_store.store_id = ?', (int)$storeId)
            ]
        );

        $select = $connection->select()->from(
            ['template_option' => $this->getMainTable()],
            null
        )->join(
            ['option_type' => $this->getTable('aitoc_optionsmanagement_template_option_type_value')],
            'option_type.option_id=template_option.option_id',
            []
        )->join(
            ['cpe' => $this->getTable('aitoc_optionsmanagement_template')],
            sprintf(
                'cpe.%s = template_option.template_id',
                $this->getMetadataPool()->getMetadata(TemplateInterface::class)->getLinkField()
            ),
            []
        )->join(
            ['option_title_default' => $this->getTable('aitoc_optionsmanagement_template_option_type_title')],
            $defaultOptionJoin,
            []
        )->joinLeft(
            ['option_title_store' => $this->getTable('aitoc_optionsmanagement_template_option_type_title')],
            $storeOptionJoin,
            ['title' => $titleCheckSql]
        )->where(
            'cpe.entity_id = ?',
            $templateId
        );

        if ($titles = $connection->fetchCol($select)) {
            $searchData = array_merge($searchData, $titles);
        }

        return $searchData;
    }

    /**
     * All Option Types that support price and price_type
     *
     * @return string[]
     */
    public function getPriceTypes()
    {
        return ['field', 'area', 'file', 'date', 'date_time', 'time'];
    }

    /**
     * @return \Magento\Framework\EntityManager\MetadataPool
     */
    private function getMetadataPool()
    {
        if (null === $this->metadataPool) {
            $this->metadataPool = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\EntityManager\MetadataPool::class);
        }
        return $this->metadataPool;
    }

    /**
     * @return string
     */
    public function getRelationProductOptionTable()
    {
        return $this->getTable('aitoc_optionsmanagement_template_product_option');
    }

    /**
     * Save template products relation
     *
     * @param \Magento\Catalog\Model\Product\Option $productOption
     * @return int The number of affected rows.
     */
    public function saveRelation(\Magento\Catalog\Model\Product\Option $productOption)
    {
        $connection = $this->getConnection();
        return $connection->insertOnDuplicate(
            $this->getRelationProductOptionTable(),
            [
                'template_option_id' => $productOption->getTemplateOptionId(),
                'product_option_id' => $productOption->getOptionId()
            ],
            ['product_option_id']
        );
    }

    /**
     * Remove related product option
     *
     * @param \Aitoc\OptionsManagement\Model\Template\Option $option
     * @return int
     */
    public function removeRelatedProductOption(\Aitoc\OptionsManagement\Model\Template\Option $option)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(
            $this->getTable('aitoc_optionsmanagement_template_product_option'),
            'product_option_id')
            ->where('template_option_id = ?', $option->getId());

        $productOptionIds = $connection->fetchCol($select);

        return $connection->delete(
            $this->getTable('catalog_product_option'),
            ['option_id IN (?)' => $productOptionIds]
        );
    }
}
