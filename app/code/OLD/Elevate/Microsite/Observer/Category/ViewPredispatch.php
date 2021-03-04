<?php


namespace Elevate\Microsite\Observer\Category;

use Magento\Framework\Event\ObserverInterface;

/**
 * observer for event controller_action_predispatch_catalog_category_view
 */
class ViewPredispatch implements ObserverInterface
{
    /**
     * @var \Elevate\Microsite\Model\ProductRuleProvider
     */
    private $ruleProvider;

    /**
     * @var \Elevate\Microsite\Helper\Data
     */
    private $helper;

    /**
     * ViewPredispatch constructor.
     *
     * @param \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider
     * @param \Elevate\Microsite\Helper\Data               $helper
     */
    public function __construct(
        \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider,
        \Elevate\Microsite\Helper\Data $helper
    ) {
        $this->ruleProvider = $ruleProvider;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        if (!$this->helper->isModuleEnabled()) {
            return;
        }

        /** @var \Magento\Framework\App\RequestInterface $request */
        $request = $observer->getEvent()->getRequest();
        $categoryId = $request->getParam('id');
        if (!$categoryId) {
            return;
        }

        $rule = $this->ruleProvider->getRulesForCategoryView($categoryId)
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();

        if ($rule->getId()) {
         //   $this->helper->setRedirect($observer->getEvent()->getControllerAction(), $rule);
        }
    }
}
