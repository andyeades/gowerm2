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
namespace Aheadworks\Giftcard\Model\Sales\Totals;

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Aheadworks\Giftcard\Model\Config;
use Aheadworks\Giftcard\Model\Giftcard\Validator\Quote as GiftcardQuoteValidator;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard as GiftcardProductType;
use Aheadworks\Giftcard\Model\Sales\Totals\Calculator\GiftCardExclude;
use Magento\Tax\Model\Calculation;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote as ModelQuote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Tax\Api\TaxCalculationInterface;
use Magento\Tax\Model\Config as TaxConfig;
use Magento\Quote\Model\Quote as QuoteModel;

/**
 * Class Quote
 *
 * @package Aheadworks\Giftcard\Model\Sales\Totals
 */
class Quote extends AbstractTotal
{
    /**
     * @var bool
     */
    private $isFirstTimeResetRun = true;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var GiftcardQuoteValidator
     */
    private $giftcardQuoteValidator;

    /**
     * @var GiftCardExclude
     */
    private $excludeCalculator;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var TaxCalculationInterface
     */
    private $taxCalculation;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var TaxConfig
     */
    private $taxConfig;

    /**
     * Quote constructor.
     * @param PriceCurrencyInterface $priceCurrency
     * @param GiftcardQuoteValidator $giftcardQuoteValidator
     * @param GiftCardExclude $excludeCalculator
     * @param ProductRepository $productRepository
     * @param TaxCalculationInterface $taxCalculation
     * @param Config $config
     * @param TaxConfig $taxConfig
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        GiftcardQuoteValidator $giftcardQuoteValidator,
        GiftCardExclude $excludeCalculator,
        ProductRepository $productRepository,
        TaxCalculationInterface $taxCalculation,
        Config $config,
        TaxConfig $taxConfig
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->giftcardQuoteValidator = $giftcardQuoteValidator;
        $this->excludeCalculator = $excludeCalculator;
        $this->productRepository = $productRepository;
        $this->taxCalculation = $taxCalculation;
        $this->config = $config;
        $this->taxConfig = $taxConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(
        ModelQuote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {

        parent::collect($quote, $shippingAssignment, $total);

        $address = $shippingAssignment->getShipping()->getAddress();
        $items = $shippingAssignment->getItems();
        $this->reset($total, $quote, $address);

        if (!count($items)) {
            return $this;
        }

        $subtractionValue = 0;
        $taxAmount = $total->getTaxAmount();
        $baseGrandTotal = $total->getBaseGrandTotal();
        $grandTotal = $total->getGrandTotal();
        $subtotal = $total->getSubtotal();
        $baseSubtotal = $total->getBaseSubtotal();
        $subtotalInclTax = $total->getSubtotalInclTax();
        $baseSubtotalInclTax = $total->getBaseSubtotalInclTax();

         foreach ($items as $item) {
            if ($item->getProductType() == GiftcardProductType::TYPE_CODE
                && $this->config->needToIncludeTaxToGiftcardBalance()
                && !$this->taxConfig->priceIncludesTax()
            ) {
                $taxAmountRow = $item->getTaxAmount();
                $item = $this->calculateGiftcard($item);

                $taxAmount += $item->getTaxAmount() - $taxAmountRow;
                $grandTotal -= $taxAmountRow;
                $baseGrandTotal -= $taxAmountRow;
                $subtotal -= $item->getTaxAmount();
                $baseSubtotal -= $item->getTaxAmount();
                $subtotalInclTax -= $taxAmountRow;
                $baseSubtotalInclTax -= $taxAmountRow;

                $subtractionValue += $taxAmountRow;
            }
        }

        if (!$quote->getExtensionAttributes()
            || ($quote->getExtensionAttributes() && !$quote->getExtensionAttributes()->getAwGiftcardCodes())
            || !$baseGrandTotal
        ) {
            $total->setGrandTotal($grandTotal);
            $total->setBaseGrandTotal($baseGrandTotal);
            $total->setSubtotal($subtotal);
            $total->setBaseSubtotal($baseSubtotal);
            $total->setSubtotalInclTax($subtotalInclTax);
            $total->setBaseSubtotalInclTax($baseSubtotalInclTax);
            $total->setTaxAmount($taxAmount);
            $total->setBaseTaxAmount($taxAmount);
            $this->reset($total, $quote, $address, true);
            return $this;
        }

        $baseTotalGiftcardAmount = $totalGiftcardAmount = 0;
        $giftcards = $quote->getExtensionAttributes()->getAwGiftcardCodes();

        if ($giftcards) {
            list($baseGrandTotal, $grandTotal) = $this->excludeCalculator->calculate(
                $quote->getAllItems(),
                $baseGrandTotal,
                $grandTotal
            );
        }

        /** @var $giftcard GiftcardQuoteInterface */
        foreach ($giftcards as $giftcard) {
            if ($giftcard->getBaseGiftcardBalanceUsed() <= 0) {
                $giftcard->setGiftcardAmount(0);
                $giftcard->setBaseGiftcardAmount(0);
            }

            $baseGiftcardUsedAmount = min(
                $giftcard->getGiftcardBalance() - $giftcard->getBaseGiftcardBalanceUsed(),
                $baseGrandTotal
            );
            if ($baseGiftcardUsedAmount <= 0 && $giftcard->getBaseGiftcardAmount() <= 0) {
                $giftcard->setIsRemove(true);
            }
            if ($giftcard->isRemove() || $baseGiftcardUsedAmount <= 0) {
                if ($quote->getIsMultiShipping()) {
                    $giftcard->setIsRemove(false);
                }
                continue;
            }
            $baseGrandTotal -= $baseGiftcardUsedAmount;

            $giftcardUsedAmount = min($this->priceCurrency->convert($baseGiftcardUsedAmount), $grandTotal);
            $grandTotal -= $giftcardUsedAmount;

            $baseTotalGiftcardAmount += $baseGiftcardUsedAmount;
            $totalGiftcardAmount += $giftcardUsedAmount;

            if ($this->config->needToIncludeTaxToGiftcardBalance()) {
                list($baseSubtotalForTax, $grandTotal) = $this->excludeCalculator->calculate(
                    $quote->getAllItems(),
                    $baseSubtotalInclTax,
                    $subtotalInclTax
                );

                foreach ($items as $item) {
                    $itemTaxPercent = $item->getTaxPercent();
                    $taxAmount += $item->getRowTotalInclTax() * $itemTaxPercent / (100 + $itemTaxPercent) - $item->getTaxAmount();
                }

                $giftcardAmountForTax = min($baseSubtotalForTax, $giftcardUsedAmount);

                if ($giftcard->getGiftcardProductId()) {
                    $giftcardTaxRate = $this->getGiftcardTaxRate($giftcard, $quote);
                    $taxAmount -= $giftcardAmountForTax * $giftcardTaxRate / (100 + $giftcardTaxRate);
                }
            }

            $baseGiftcardBalanceUsed = $giftcard->getBaseGiftcardBalanceUsed() + $baseGiftcardUsedAmount;
            $giftcardBalanceUsed = $giftcard->getGiftcardBalanceUsed() + $giftcardUsedAmount;
            $giftcard
                ->setBaseGiftcardAmount($baseGiftcardBalanceUsed)
                ->setGiftcardAmount($giftcardBalanceUsed)
                ->setBaseGiftcardBalanceUsed($baseGiftcardBalanceUsed)
                ->setGiftcardBalanceUsed($giftcardBalanceUsed);
        }
        $baseGrandTotal = $total->getBaseGrandTotal() - $baseTotalGiftcardAmount - $subtractionValue;
        $grandTotal = $total->getGrandTotal() - $totalGiftcardAmount - $subtractionValue;

        $this
            ->_addBaseAmount($baseTotalGiftcardAmount)
            ->_addAmount($totalGiftcardAmount);
        $total
            ->setBaseAwGiftcardAmount($baseTotalGiftcardAmount)
            ->setAwGiftcardAmount($totalGiftcardAmount)
            ->setBaseGrandTotal($baseGrandTotal)
            ->setGrandTotal($grandTotal)
            ->setSubtotal($subtotal)
            ->setBaseSubtotal($baseSubtotal)
            ->setSubtotalInclTax($subtotalInclTax)
            ->setBaseSubtotalInclTax($baseSubtotalInclTax)
            ->setTaxAmount($taxAmount);

        $quote
            ->setBaseAwGiftcardAmount($quote->getBaseAwGiftcardAmount() + $baseTotalGiftcardAmount)
            ->setAwGiftcardAmount($quote->getAwGiftcardAmount() + $totalGiftcardAmount);
        $address
            ->setBaseAwGiftcardAmount($baseTotalGiftcardAmount)
            ->setAwGiftcardAmount($totalGiftcardAmount);

        return $this;
    }

    /**
     * Get giftcard tax rate
     *
     * @param GiftcardQuoteInterface $giftcard
     * @param QuoteModel $quote
     * @return float
     * @throws NoSuchEntityException
     */
    private function getGiftcardTaxRate($giftcard, $quote)
    {
        $giftcardProductId = $giftcard->getGiftcardProductId();
        $giftcardProduct = $this->productRepository->getById($giftcardProductId);
        return $this->taxCalculation->getCalculatedRate($giftcardProduct->getTaxClassId(), $quote->getCustomerId());
    }

    /**
     * Calculate giftcard
     *
     * @param CartItemInterface $giftcard
     * @return CartItemInterface
     */
    private function calculateGiftcard($giftcard)
    {
        $taxPercent = $giftcard->getTaxPercent() * 0.01;
        $taxForItem = $giftcard->getPrice() * $taxPercent / (1 + $taxPercent);
        $itemPriceWithoutTax = $giftcard->getPrice() - $taxForItem;
        $qty = $giftcard->getQty();

        $giftcard->setTaxAmount($taxForItem * $qty);
        $giftcard->setBaseTaxAmount($taxForItem * $qty);
        $giftcard->setTaxCalculationPrice($itemPriceWithoutTax);
        $giftcard->setBaseTaxCalculationPrice($itemPriceWithoutTax);

        $giftcard->setPriceInclTax($giftcard->getPrice());
        $giftcard->setPrice($itemPriceWithoutTax);
        $giftcard->setBasePriceInclTax($giftcard->getBasePrice());
        $giftcard->setBasePrice($itemPriceWithoutTax);
        $giftcard->setConvertedPrice($itemPriceWithoutTax);
        $giftcard->setBaseCalculationPrice($itemPriceWithoutTax);
        $giftcard->setBaseOriginalPrice($itemPriceWithoutTax);

        $giftcard->setRowTotalInclTax($giftcard->getRowTotal());
        $giftcard->setRowTotal($itemPriceWithoutTax * $qty);
        $giftcard->setBaseRowTotalInclTax($giftcard->getBaseRowTotal());
        $giftcard->setBaseRowTotal($itemPriceWithoutTax * $qty);

        return $giftcard;
    }

    /**
     * Reset Gift Card total
     *
     * @param Total $total
     * @param ModelQuote $quote
     * @param AddressInterface $address
     * @param bool $reset
     * @return $this
     */
    private function reset(Total $total, ModelQuote $quote, AddressInterface $address, $reset = false)
    {
        if ($this->isFirstTimeResetRun || $reset) {
            $this->_addAmount(0);
            $this->_addBaseAmount(0);

            $total->setBaseAwGiftcardAmount(0);
            $total->setAwGiftcardAmount(0);

            $quote->setBaseAwGiftcardAmount(0);
            $quote->setAwGiftcardAmount(0);

            $address->setBaseAwGiftcardAmount(0);
            $address->setAwGiftcardAmount(0);

            if ($reset && $quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getAwGiftcardCodes()) {
                $giftcards = $quote->getExtensionAttributes()->getAwGiftcardCodes();
                /** @var $giftcard GiftcardQuoteInterface */
                foreach ($giftcards as $giftcard) {
                    $giftcard->setIsRemove(true);
                }
            }

            $this->isFirstTimeResetRun = false;
        }
        return $this;
    }

    /**
     * Add Gift Card
     *
     * @param ModelQuote $quote
     * @param Total $total
     * @return []
     */
    public function fetch(ModelQuote $quote, Total $total)
    {
        $giftcards = [];
        if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getAwGiftcardCodes()) {
            $giftcards = $quote->getExtensionAttributes()->getAwGiftcardCodes();
        }
        if (!empty($giftcards)) {
            return [
                'code' => $this->getCode(),
                'aw_giftcard_codes' => $giftcards,
                'title' => __('Gift Card'),
                'value' => -$total->getAwGiftcardAmount()
            ];
        }

        return null;
    }
}
