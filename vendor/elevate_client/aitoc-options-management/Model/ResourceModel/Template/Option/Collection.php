<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\ResourceModel\Template\Option;

/**
 * Catalog template options collection
 *
 * @api
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Option value factory
     *
     * @var \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\CollectionFactory
     */
    protected $_optionValueCollectionFactory;

    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\CollectionFactory $optionValueCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\CollectionFactory $optionValueCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Aitoc\OptionsManagement\Helper\Data $helper,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

        $this->_optionValueCollectionFactory = $optionValueCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Aitoc\OptionsManagement\Model\Template\Option::class,
            \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option::class
        );
    }

    /**
     * @param int $templateId
     * @param int $storeId
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface[]
     */
    public function getOptionsByTemplate($templateId, $storeId = 0)
    {
        $this->addFieldToFilter('template_id', $templateId)
            ->addTitleToResult($storeId)
            ->addPriceToResult($storeId)
            ->setOrder('sort_order', 'asc')
            ->setOrder('title', 'asc');

        if ($this->helper->isDefaultValueEnabled()) {
            $this->addDefaultTextToResult($storeId);
        }

        if ($this->helper->isEnabledPerOptionEnabled()) {
            $this->addIsEnableToResult($storeId);
        }

        $this->addValuesToResult($storeId);
        return $this->getItems();
    }

    /**
     * Add title to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addTitleToResult($storeId)
    {
        $templateOptionTitleTable = $this->getTable('aitoc_optionsmanagement_template_option_title');
        $connection = $this->getConnection();
        $titleExpr = $connection->getCheckSql(
            'store_option_title.title IS NULL',
            'default_option_title.title',
            'store_option_title.title'
        );

        $this->getSelect()->join(
            ['default_option_title' => $templateOptionTitleTable],
            'default_option_title.option_id = main_table.option_id',
            ['default_title' => 'title']
        )->joinLeft(
            ['store_option_title' => $templateOptionTitleTable],
            'store_option_title.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_title.store_id = ?',
                $storeId
            ),
            ['store_title' => 'title', 'title' => $titleExpr]
        )->where(
            'default_option_title.store_id = ?',
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );

        return $this;
    }

    /**
     * Add default text to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addDefaultTextToResult($storeId)
    {
        $templateOptionDefaultTextTable = $this->getTable('aitoc_optionsmanagement_template_option_default');
        $connection = $this->getConnection();
        $defaultTextExpr = $connection->getCheckSql(
            'store_option_default_text.default_text IS NULL',
            'default_option_default_text.default_text',
            'store_option_default_text.default_text'
        );

        $this->getSelect()->joinLeft(
            ['default_option_default_text' => $templateOptionDefaultTextTable],
            'default_option_default_text.option_id = main_table.option_id AND '
                . 'default_option_default_text.store_id = ' . \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ['default_default_text' => 'default_text']
        )->joinLeft(
            ['store_option_default_text' => $templateOptionDefaultTextTable],
            'store_option_default_text.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_default_text.store_id = ?',
                $storeId
            ),
            ['store_default_text' => 'default_text', 'default_text' => $defaultTextExpr]
        );

        return $this;
    }

    /**
     * Add is enable to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addIsEnableToResult($storeId)
    {
        $templateOptionIsEnableTable = $this->getTable('aitoc_optionsmanagement_template_option_is_enable');
        $connection = $this->getConnection();
        $isEnableExpr = $connection->getCheckSql(
            'store_option_is_enable.is_enable IS NULL',
            $connection->getCheckSql(
                'default_option_is_enable.is_enable IS NULL',
                '1',
                'default_option_is_enable.is_enable'
            ),
            'store_option_is_enable.is_enable'
        );

        $this->getSelect()->joinLeft(
            ['default_option_is_enable' => $templateOptionIsEnableTable],
            'default_option_is_enable.option_id = main_table.option_id AND '
            . 'default_option_is_enable.store_id = ' . \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ['default_is_enable' => 'is_enable']
        )->joinLeft(
            ['store_option_is_enable' => $templateOptionIsEnableTable],
            'store_option_is_enable.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_is_enable.store_id = ?',
                $storeId
            ),
            ['store_is_enable' => 'is_enable', 'is_enable' => $isEnableExpr]
        );

        return $this;
    }

    /**
     * Add price to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addPriceToResult($storeId)
    {
        $templateOptionPriceTable = $this->getTable('aitoc_optionsmanagement_template_option_price');
        $connection = $this->getConnection();
        $priceExpr = $connection->getCheckSql(
            'store_option_price.price IS NULL',
            'default_option_price.price',
            'store_option_price.price'
        );
        $priceTypeExpr = $connection->getCheckSql(
            'store_option_price.price_type IS NULL',
            'default_option_price.price_type',
            'store_option_price.price_type'
        );

        $this->getSelect()->joinLeft(
            ['default_option_price' => $templateOptionPriceTable],
            'default_option_price.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'default_option_price.store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            ),
            ['default_price' => 'price', 'default_price_type' => 'price_type']
        )->joinLeft(
            ['store_option_price' => $templateOptionPriceTable],
            'store_option_price.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_price.store_id = ?',
                $storeId
            ),
            [
                'store_price' => 'price',
                'store_price_type' => 'price_type',
                'price' => $priceExpr,
                'price_type' => $priceTypeExpr
            ]
        );

        return $this;
    }

    /**
     * Add value to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addValuesToResult($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->_storeManager->getStore()->getId();
        }
        $optionIds = [];

        foreach ($this as $option) {
            $optionIds[] = $option->getId();
        }

        if (!empty($optionIds)) {
            /** @var \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\Collection $values */
            $values = $this->_optionValueCollectionFactory->create();
            $values->addTitleToResult($storeId)
                ->addPriceToResult($storeId)
                ->addOptionToFilter($optionIds)
                ->setOrder('sort_order', self::SORT_ORDER_ASC)
                ->setOrder('title', self::SORT_ORDER_ASC);

            foreach ($values as $value) {
                $optionId = $value->getOptionId();
                if ($this->getItemById($optionId)) {
                    $this->getItemById($optionId)->addValue($value);
                    $value->setOption($this->getItemById($optionId));
                }
            }
        }

        return $this;
    }

    /**
     * Adds title, price & price_type attributes to result
     *
     * @param int $storeId
     * @return $this
     */
    public function getOptions($storeId)
    {
        $this->addPriceToResult($storeId)->addTitleToResult($storeId);

        return $this;
    }

    /**
     * Add is_required filter to select
     *
     * @param bool $required
     * @return $this
     */
    public function addRequiredFilter($required = true)
    {
        $this->addFieldToFilter('main_table.is_require', (int)$required);
        return $this;
    }

    /**
     * Add filtering by option ids
     *
     * @param string|array $optionIds
     * @return $this
     */
    public function addIdsToFilter($optionIds)
    {
        $this->addFieldToFilter('main_table.option_id', $optionIds);
        return $this;
    }

    /**
     * Call of protected method reset
     *
     * @return $this
     */
    public function reset()
    {
        return $this->_reset();
    }
}
