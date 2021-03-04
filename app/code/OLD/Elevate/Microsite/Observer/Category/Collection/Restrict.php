<?php

namespace Elevate\Microsite\Observer\Category\Collection;

use Magento\Framework\Event\ObserverInterface;

/**
 * observer for event catalog_category_collection_load_before
 */
class Restrict implements ObserverInterface
{
    use \Elevate\Microsite\Observer\CatalogCollectionTrait;

    /**
     * @var \Elevate\Microsite\Model\ProductRuleProvider
     */
    private $ruleProvider;

    /**
     * @var \Elevate\Microsite\Helper\Data
     */
    private $helper;

    /**
     * Restrict constructor.
     *
     * @param \Elevate\Microsite\Model\ProductRuleProvider                  $ruleProvider
     * @param \Elevate\Microsite\Helper\Data                                $helper
     */
    public function __construct(
        \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider,
        \Elevate\Microsite\Helper\Data $helper
    ) {
        $this->ruleProvider = $ruleProvider;
        $this->helper       = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->isModuleEnabled()) {
            $this->restrictCollectionIds(
                $observer->getEvent()->getCategoryCollection(),
                $this->ruleProvider->getRestrictCategoriesId()
            );
        }
    }
}
