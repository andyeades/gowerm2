<?php


namespace Elevate\Microsite\Observer\Product;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Observer for event controller_action_predispatch_catalog_product_view
 */
class ViewPredispatch implements ObserverInterface
{
    /**
     * @var \Elevate\Microsite\Model\ProductRuleProvider
     */
    private $ruleProvider;

    /**
     * @var \Elevate\Microsite\Model\RuleRepository
     */
    private $ruleRepository;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;

    /**
     * @var \Magento\Cms\Helper\Page
     */
    private $pageHelper;

    /**
     * @var \Elevate\Microsite\Helper\Data
     */
    private $helper;

    /**
     * ViewPredispatch constructor.
     *
     * @param \Elevate\Microsite\Helper\Data               $helper
     * @param \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider
     * @param \Elevate\Microsite\Model\RuleRepository      $ruleRepository
     * @param \Magento\Catalog\Model\ProductRepository   $productRepository
     * @param \Magento\Cms\Helper\Page                   $pageHelper
     */
    public function __construct(
        \Elevate\Microsite\Helper\Data $helper,
        \Elevate\Microsite\Model\ProductRuleProvider $ruleProvider,
        \Elevate\Microsite\Model\RuleRepository $ruleRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Cms\Helper\Page $pageHelper
    ) {
        $this->ruleProvider      = $ruleProvider;
        $this->ruleRepository    = $ruleRepository;
        $this->productRepository = $productRepository;
        $this->pageHelper        = $pageHelper;
        $this->helper            = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        if (!$this->helper->isModuleEnabled()) {
            return;
        }
        /** @var \Magento\Framework\App\RequestInterface $request */
        $request = $observer->getEvent()->getRequest();
        $productId = $request->getParam('id');
        if (!$productId) {
            return;
        }

        $product = $this->productRepository->getById($productId);
        if (!$product) {
            return;
        }

        $ruleIndex = $this->ruleProvider->getRuleForProduct($product);
        if (!$ruleIndex || !array_key_exists('rule_id', $ruleIndex) || !$ruleIndex['rule_id']) {
            return;
        }

        $rule = $this->ruleRepository->get($ruleIndex['rule_id']);
        $this->helper->setRedirect($observer->getEvent()->getControllerAction(), $rule);
    }
}
