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
namespace Aheadworks\Giftcard\Model\Source\Giftcard;

use Magento\Framework\Option\ArrayInterface;
use Magento\Config\Model\Config\Source\Email\Template as SourceEmailTemplate;

/**
 * Class EmailTemplate
 *
 * @package Aheadworks\Giftcard\Model\Source\Giftcard
 */
class EmailTemplate implements ArrayInterface
{
    /**
     * 'Do not send' option value
     */
    const DO_NOT_SEND = '0';

    /**
     * @var SourceEmailTemplate
     */
    private $emailTemplates;

    /**
     * @param SourceEmailTemplate $emailTemplates
     */
    public function __construct(
        SourceEmailTemplate $emailTemplates
    ) {
        $this->emailTemplates = $emailTemplates;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $optionArray = $this->emailTemplates
            ->setPath('aw_giftcard_email_template')
            ->toOptionArray();

        array_unshift(
            $optionArray,
            [
                'value' => self::DO_NOT_SEND,
                'label' => __('Do not send')
            ]
        );
        return $optionArray;
    }
}
