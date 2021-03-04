<?php
declare(strict_types=1);
/**
 * ProcessProductOptions
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Model;

use Firebear\ConfigurableProducts\Logger\Logger;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Repository;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Firebear\ConfigurableProducts\Framework\Serializer\Json;

/**
 * Class ProcessProductOptions
 * @package Firebear\ConfigurableProducts\Model
 */
class ProcessProductOptions
{
    /**
     * @var Option
     */
    protected $optionModel;

    /**
     * @var Json|mixed|null
     */
    protected $serializer;

    /**
     * @var Repository
     */
    protected $optionRepository;

    /**
     * @var ProductOptionsRepository
     */
    protected $productOptionsRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Configurable
     */
    protected $configurableProducts;

    /**
     * ProcessProductOptions constructor.
     * @param Option $optionModel
     * @param Repository $optionRepository
     * @param ProductOptionsRepository $productOptionsRepository
     * @param ProductRepository $productRepository
     * @param Logger $logger
     * @param Configurable $configurableProducts
     * @param Json|null $serializer
     */
    public function __construct(
        Option $optionModel,
        Repository $optionRepository,
        ProductOptionsRepository $productOptionsRepository,
        ProductRepository $productRepository,
        Logger $logger,
        Configurable $configurableProducts,
        Json $serializer = null
    ) {
        $this->optionRepository = $optionRepository;
        $this->optionModel = $optionModel;
        $this->productOptionsRepository = $productOptionsRepository;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->serializer = $serializer
            ?: ObjectManager::getInstance()
                ->get(Json::class);
        $this->configurableProducts = $configurableProducts;
    }

    /**
     * @param $params
     * @param Product $product
     * @param $simpleProductId
     * @param bool $matrixSwatch
     * @return mixed
     */
    public function addCustomizibleOpionsToProduct(
        &$params,
        &$product,
        $simpleProductId = null,
        $matrixSwatch = false
    ) {
        $productId = null;
        $additionalOptions = [];
        $paramsForNextProduct = $params;
        try {
            if ($product && $product->getTypeId() === Configurable::TYPE_CODE) {
                $childProduct = $this->configurableProducts->getProductByAttributes(
                    $params['super_attribute'],
                    $product
                );
            }
            if (isset($params['options'])) {
                foreach ($params['options'] as $optionId => $option) {
                    if (empty($option)) {
                        continue;
                    }
                    if ($childProduct) {
                        $productId = $childProduct->getId();
                    } else {
                        $optionModel = $this->optionModel->load($optionId);
                        $productId = $optionModel->getProductId();
                    }
                    $productOption = $this->productRepository->getById($productId);
                    if ($productOption->getTypeId() == 'configurable') {
                        continue;
                    }
                    $addCustomOptions = !$simpleProductId || $productId == $simpleProductId;
                    $sku = $productOption->getSku();
                    if ($matrixSwatch) {
                        $sku = $productOption->getData('sku');
                    }
                    try {
                        $optionModel = $this->optionRepository->get(
                            $sku,
                            $optionId
                        );
                    } catch (\Exception $exception) {
                        continue;
                    }
                    $optionValue = null;
                    if ($addCustomOptions) {
                        foreach ($productOption->getOptions() as $optionProduct) {
                            if ($optionProduct->getOptionId() == $optionId) {
                                $optionData = $optionProduct->getValues();
                                if ($optionProduct->getType() == 'field' || $optionProduct->getType() == 'area'
                                    || $optionProduct->getType() == 'date'
                                    || $optionProduct->getType() == 'date_time'
                                    || $optionProduct->getType() == 'time') {
                                    if ($optionProduct->getType() == 'date') {
                                        $valueString = $option['day'] . "/" . $option['month'] . "/" . $option['year'];
                                    } elseif ($optionProduct->getType() == 'date_time') {
                                        $valueString = $option['day'] . "/" . $option['month'] . "/" . $option['year'] . " "
                                            . $option['hour'] . ":" . $option['minute'] . " " . strtoupper($option['day_part']);
                                    } elseif ($optionProduct->getType() == 'time') {
                                        $valueString = $option['hour'] . ":" . $option['minute'] . " " . strtoupper($option['day_part']);
                                    } else {
                                        $valueString = $option;
                                    }
                                    $optionValue = $valueString;
                                } elseif (is_array($optionData)) {
                                    foreach ($optionData as $data) {
                                        if (!is_array($option)) {
                                            if ($option == $data->getOptionTypeId()) {
                                                $optionValue = $data->getTitle();
                                            }
                                        } else {
                                            foreach ($option as $val) {
                                                if ($val == $data->getOptionTypeId()) {
                                                    $optionValue .= $data->getTitle() . ' ';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $optionTitle = $optionModel->getTitle();
                        $additionalOptions[] = [
                            'label' => $optionTitle,
                            'value' => $optionValue,
                        ];
                    }
                }
                if (!empty($additionalOptions)) {
                    $product->addCustomOption('additional_options', $this->serializer->serialize($additionalOptions));
                }
            }
        } catch (NoSuchEntityException $noSuchEntityException) {
            $message = sprintf(
                '%s %s %s for productID %s',
                __CLASS__,
                __METHOD__,
                $noSuchEntityException->getMessage(),
                $productId
            );
            $this->logger->info($message);
        }
        return $paramsForNextProduct;
    }
}
