<?php
namespace Elevate\PrintLabels\Model\Order\Pdf;

/**
 * Use built-in fonts in PDFs so that invoices are smaller.
 *
 */
class Invoice extends \Magento\Sales\Model\Order\Pdf\Invoice
{
    protected function _setFontRegular($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA); // or FONT_TIMES for serif
        $object->setFont($font, $size);
        return $font;
    }

    protected function _setFontBold($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD); // or FONT_TIMES_BOLD for serif
        $object->setFont($font, $size);
        return $font;
    }

    protected function _setFontItalic($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_ITALIC); // or FONT_TIMES_ITALIC for serif
        $object->setFont($font, $size);
        return $font;
    }
}