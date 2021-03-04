<?php


namespace Elevate\Microsite\Observer\Category;

use Amasty\Groupcat\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Event\ObserverInterface;

/**
 * observer for event catalog_category_tree_init_inactive_category_ids
 */
class AddInactive implements ObserverInterface
{
    /**
     * @var \Elevate\MicrositeModel\ProductRuleProvider
     */
    private $ruleProvider;

    /**
     * @var \Elevate\Microsite\Helper\Data
     */
    private $helper;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * AddInactive constructor.
     *
     * @param \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider
     * @param CollectionFactory                          $collectionFactory
     * @param \Elevate\Microsite\Helper\Data               $helper
     */
    public function __construct(
        \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider,
        \Elevate\Microsite\Model\ResourceModel\Rule\CollectionFactory $collectionFactory,
        \Elevate\Microsite\Helper\Data $helper
    ) {
        $this->ruleProvider = $ruleProvider;
        $this->helper       = $helper;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->helper->isModuleEnabled()) {
            return;
        }
        $categoryIds = $this->ruleProvider->getRestrictCategoriesId();
        if (is_array($categoryIds) && count($categoryIds)) {
            $observer->getEvent()->getTree()->addInactiveCategoryIds($categoryIds);
        }
    }
}
