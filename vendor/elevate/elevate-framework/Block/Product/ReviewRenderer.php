<?php
/**
 * Review renderer
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Elevate\Framework\Block\Product;

use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Review\Model\ReviewSummaryFactory;
use Magento\Review\Observer\PredispatchReviewObserver;

/**
 * Class ReviewRenderer
 */
class ReviewRenderer extends \Magento\Review\Block\Product\ReviewRenderer implements ReviewRendererInterface
{
    /**
     * Review model factory
     *
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $_reviewFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        array $data = []
    ) {
        $this->_reviewFactory = $reviewFactory;
        parent::__construct($context, $reviewFactory, $data);
    }



    /**
     * Get ratings summary
     *
     * @return string
     */
    public function getProductCurrent()
    {
        return $this->getProduct();
    }

}
