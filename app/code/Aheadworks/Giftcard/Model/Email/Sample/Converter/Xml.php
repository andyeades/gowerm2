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
namespace Aheadworks\Giftcard\Model\Email\Sample\Converter;

use Magento\Framework\Config\ConverterInterface;

/**
 * Class Xml
 *
 * @package Aheadworks\Giftcard\Model\Email\Sample\Converter
 */
class Xml implements ConverterInterface
{
    /**
     * Converting data to array type
     *
     * @param mixed $source
     * @return array
     * @throws \InvalidArgumentException
     */
    public function convert($source)
    {
        $output = [];
        if (!$source instanceof \DOMDocument) {
            return $output;
        }

        $events = $source->getElementsByTagName('template');
        foreach ($events as $event) {
            $eventData = [];
            /** @var $event \DOMElement */
            foreach ($event->childNodes as $child) {
                if (!$child instanceof \DOMElement) {
                    continue;
                }
                /** @var $event \DOMElement */
                $eventData[$child->nodeName] = $child->nodeValue;
            }
            $output[] = $eventData;
        }
        return $output;
    }
}
