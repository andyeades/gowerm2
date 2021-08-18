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
 * @package    GiftcardGraphQl
 * @version    1.0.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\GiftcardGraphQl\Model\Resolver\Product;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\Mail\Template\FactoryInterface as TemplateFactory;
use Magento\Email\Model\Template\Config as EmailConfig;
use Magento\Email\Model\Template as EmailTemplate;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Media\Config as MediaConfig;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard as GiftCardType;

/**
 * Class EmailTemplateList
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\Product
 */
class EmailTemplateList implements ResolverInterface
{
    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var EmailConfig
     */
    private $emailConfig;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var MediaConfig
     */
    private $mediaConfig;

    /**
     * @param TemplateFactory $templateFactory
     * @param EmailConfig $emailConfig
     * @param ProductRepositoryInterface $productRepository
     * @param MediaConfig $mediaConfig
     */
    public function __construct(
        TemplateFactory $templateFactory,
        EmailConfig $emailConfig,
        ProductRepositoryInterface $productRepository,
        MediaConfig $mediaConfig
    ) {
        $this->templateFactory = $templateFactory;
        $this->emailConfig = $emailConfig;
        $this->productRepository = $productRepository;
        $this->mediaConfig = $mediaConfig;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('"model" value should be specified'));
        }

        /** @var Product $product */
        $model = $value['model'];
        $product = $this->productRepository->getById($model->getId());

        $data = [];
        if ($product->getTypeId() === GiftCardType::TYPE_CODE) {
            /** @var GiftCardType $typeInstance */
            $typeInstance = $product->getTypeInstance();
            $templateOptions = $typeInstance->getTemplateOptions($product);
            foreach ($templateOptions as $option) {
                $data[] = [
                    'value' => $option['template'],
                    'name' => $this->getTemplateName($option['template']),
                    'image_url' => $option['image'] ? $this->mediaConfig->getTmpMediaUrl($option['image']) : ''
                ];
            }
        }

        return $data;
    }

    /**
     * Retrieve template name
     *
     * @param int|string $templateId
     * @return string
     */
    private function getTemplateName($templateId)
    {
        /** @var EmailTemplate $template */
        $template = $this->templateFactory->get($templateId);
        if (is_numeric($templateId)) {
            return $template->load($templateId)->getTemplateCode();
        } else {
            return $this->emailConfig->getTemplateLabel($templateId);
        }
    }
}
