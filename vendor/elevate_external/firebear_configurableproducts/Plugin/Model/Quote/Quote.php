<?php

namespace Firebear\ConfigurableProducts\Plugin\Model\Quote;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Item\Processor;
use Magento\Framework\Event\ManagerInterface;
use Magento\Catalog\Model\Product\Type\AbstractType;

class Quote
{
    /**
     * @var Processor
     */
    protected $itemProcessor;

    /**
     * Application Event Dispatcher
     *
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * Quote plugin constructor.
     * @param Processor $itemProcessor
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        Processor $itemProcessor,
        ManagerInterface $eventManager

    ) {
        $this->itemProcessor = $itemProcessor;
        $this->eventManager = $eventManager;
    }


    /**
     * @param $subject
     * @param callable $proceed
     * @param Product $product
     * @param null $request
     * @param $processMode
     * @return string|null
     * @throws LocalizedException
     */
    public function aroundAddProduct(
        $subject,
        callable $proceed,
        Product $product,
        $request = null,
        $processMode = AbstractType::PROCESS_MODE_FULL
    ) {

        $superAttribute = '';
        $bundleOptions = '';
        $productType = $product->getTypeId();
        if (!is_numeric($request)) {
            $superAttribute = $request->getData('super_attribute');
            $bundleOptions = $request->getData('bundle_option');
        }
        $newPositionForConfigurableOption = ($superAttribute && $bundleOptions) ? true : false;
        if ($productType == 'bundle' && $newPositionForConfigurableOption) {
            if ($request === null) {
                $request = 1;
            }
            if (is_numeric($request)) {
                $request = $subject->objectFactory->create(['qty' => $request]);
            }

            if (!$request instanceof \Magento\Framework\DataObject) {
                throw new LocalizedException(
                    __('We found an invalid request for adding product to quote.')
                );
            }

            if (!$product->isSalable()) {
                throw new LocalizedException(
                    __('Product that you are trying to add is not available.')
                );
            }

            $cartCandidates = $product->getTypeInstance()->prepareForCartAdvanced($request, $product, $processMode);

            /**
             * Error message
             */
            if (is_string($cartCandidates) || $cartCandidates instanceof \Magento\Framework\Phrase) {
                return (string)$cartCandidates;
            }

            /**
             * If prepare process return one object
             */
            if (!is_array($cartCandidates)) {
                $cartCandidates = [$cartCandidates];
            }

            $parentItem = null;
            $errors = [];
            $item = null;
            $items = [];
            foreach ($cartCandidates as $candidate) {
                // Child items can be sticked together only within their parent
                $stickWithinParent = $candidate->getParentProductId() ? $parentItem : null;
                $candidate->setStickWithinParent($stickWithinParent);

                $item = $subject->getItemByProduct($candidate);
                if (!$item || $newPositionForConfigurableOption) {
                    $item = $this->itemProcessor->init($candidate, $request);
                    $item->setQuote($subject);
                    $item->setOptions($candidate->getCustomOptions());
                    $item->setProduct($candidate);
                    // Add only item that is not in quote already
                    $subject->addItem($item);
                }
                $items[] = $item;

                /**
                 * As parent item we should always use the item of first added product
                 */
                if (!$parentItem) {
                    $parentItem = $item;
                }
                if ($parentItem && $candidate->getParentProductId() && !$item->getParentItem()) {
                    $item->setParentItem($parentItem);
                }

                $this->itemProcessor->prepare($item, $request, $candidate);

                // collect errors instead of throwing first one
                if ($item->getHasError()) {
                    foreach ($item->getMessage(false) as $message) {
                        if (!in_array($message, $errors)) {
                            // filter duplicate messages
                            $errors[] = $message;
                        }
                    }
                }
            }
            if (!empty($errors)) {
                throw new LocalizedException(__(implode("\n", $errors)));
            }

            $this->eventManager->dispatch('sales_quote_product_add_after', ['items' => $items]);
            return $parentItem;
        } else {
            return $proceed($product, $request, $processMode);
        }
    }
}
