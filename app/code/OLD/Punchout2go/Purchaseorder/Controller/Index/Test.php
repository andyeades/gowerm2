<?php

namespace Punchout2go\Purchaseorder\Controller\Index;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPost;

class Test extends \Magento\Framework\App\Action\Action implements HttpPost,CsrfAwareActionInterface
{

    /** @var \Magento\Sales\Api\OrderItemRepositoryInterface */
    protected $_orderItemRepository;

    /** @var \Magento\Framework\Api\SearchCriteriaBuilder */
    protected $_searchCriteriaBuilder;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ){
        $this->_orderItemRepository = $orderItemRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        $searchCriteria = $this->_searchCriteriaBuilder
            ->addFilter('quote_item_id', '135', 'eq')
            ->create();

        /** @var \Magento\Quote\Model\Quote\Item $orderItem */
        $orderItems = $this->_orderItemRepository->getList($searchCriteria);

        foreach ($orderItems as $orderItem) {
            echo $orderItem->getProduct()->getName() . "\n";
        }

        die($orderItem->getQuote()->getReservedOrderId());
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

}