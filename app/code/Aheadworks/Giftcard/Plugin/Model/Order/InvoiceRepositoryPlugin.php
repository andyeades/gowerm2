<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Plugin\Model\Order;

use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Api\Data\InvoiceSearchResultInterface;
use Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Invoice\CollectionFactory as GiftcardInvoiceCollectionFactory;

/**
 * Class InvoiceRepositoryPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model\Order
 */
class InvoiceRepositoryPlugin
{
    /**
     * @var InvoiceExtensionFactory
     */
    private $invoiceExtensionFactory;

    /**
     * @var GiftcardInvoiceCollectionFactory
     */
    private $giftcardInvoiceCollectionFactory;

    /**
     * @param InvoiceExtensionFactory $invoiceExtensionFactory
     * @param GiftcardInvoiceCollectionFactory $giftcardInvoiceCollectionFactory
     */
    public function __construct(
        InvoiceExtensionFactory $invoiceExtensionFactory,
        GiftcardInvoiceCollectionFactory $giftcardInvoiceCollectionFactory
    ) {
        $this->invoiceExtensionFactory = $invoiceExtensionFactory;
        $this->giftcardInvoiceCollectionFactory = $giftcardInvoiceCollectionFactory;
    }

    /**
     * Add Gift Card codes to invoice object
     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(InvoiceRepositoryInterface $subject, InvoiceInterface $invoice)
    {
        return $this->addGiftcardDataToInvoice($invoice);
    }

    /**
     * Add Gift Card data to order object
     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceSearchResultInterface $invoices
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(InvoiceRepositoryInterface $subject, InvoiceSearchResultInterface $invoices)
    {
        /** @var InvoiceInterface $order */
        foreach ($invoices->getItems() as $invoice) {
            $this->addGiftcardDataToInvoice($invoice);
        }
        return $invoices;
    }

    /**
     * Add Gift Card data to invoice
     *
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     */
    public function addGiftcardDataToInvoice($invoice)
    {
        if ($invoice->getExtensionAttributes() && $invoice->getExtensionAttributes()->getAwGiftcardCodes()) {
            return $invoice;
        }

        $giftcardInvoiceItems = $this->giftcardInvoiceCollectionFactory->create()
            ->addFieldToFilter('invoice_id', $invoice->getEntityId())
            ->load()
            ->getItems();

        if (!$giftcardInvoiceItems) {
            return $invoice;
        }

        /** @var \Magento\Sales\Api\Data\InvoiceExtension $invoiceExtension */
        $invoiceExtension = $invoice->getExtensionAttributes()
            ? $invoice->getExtensionAttributes()
            : $this->invoiceExtensionFactory->create();

        $invoiceExtension->setBaseAwGiftcardAmount($invoice->getBaseAwGiftcardAmount());
        $invoiceExtension->setAwGiftcardAmount($invoice->getAwGiftcardAmount());
        $invoiceExtension->setAwGiftcardCodes($giftcardInvoiceItems);
        $invoice->setExtensionAttributes($invoiceExtension);
        return $invoice;
    }
}
