<?php


namespace Firebear\ConfigurableProducts\Observer;

use Firebear\ConfigurableProducts\Helper\Data;
use Firebear\ConfigurableProducts\Model\ProductOptions;
use Firebear\ConfigurableProducts\Model\ProductOptionsFactory;
use Firebear\ConfigurableProducts\Model\ProductOptionsRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\ValidatorException;

class IcpOptionsSave implements ObserverInterface
{
    const DEFAULT_AXIS_VALUE = 'default';

    protected $helper;
    protected $productOptionsModel;
    protected $productOptionsRepository;

    /**
     * DimensionalShippingOptionsSave constructor.
     *
     * @param Data $helper
     * @param ProductOptionsRepository $productOptionsRepository
     * @param ProductOptionsFactory $productOptionsModel
     */
    public function __construct(
        Data $helper,
        ProductOptionsRepository $productOptionsRepository,
        ProductOptionsFactory $productOptionsModel
    ) {
        $this->helper = $helper;
        $this->productOptionsRepository = $productOptionsRepository;
        $this->productOptionsModel = $productOptionsModel;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($observer->getEvent()->getObject()->getEventPrefix() == 'catalog_product') {
            $data = $observer->getEvent()->getObject()->getData();
            $productId = $observer->getEvent()->getObject()->getId();
            $currentItem = $this->productOptionsRepository->getByProductId($productId);
            if ($this->helper->getGeneralConfig('matrix/matrix_swatch')) {
                if (!isset($data['x_axis']) || $data['x_axis'] == self::DEFAULT_AXIS_VALUE) {
                    $data['x_axis'] = $this->helper->getGeneralConfig('matrix/x_axis');
                }
                if (!isset($data['y_axis']) || $data['y_axis'] == self::DEFAULT_AXIS_VALUE) {
                    $data['y_axis'] = $this->helper->getGeneralConfig('matrix/y_axis');
                }
                if ($data['x_axis'] == $data['y_axis']) {
                    throw new ValidatorException(
                        __('\'Attribute code for matrix X axis\'
                    and \'Attribute code for matrix Y axis\' shouldn\'t be equal.')
                    );
                }
                if (!empty($data['x_axis']) && !empty($data['y_axis'])) {
                    if (!$currentItem) {
                        $currentItem = $this->productOptionsModel->create();
                    }
                    $currentItem->setData('product_id', $observer->getEvent()->getObject()->getId());
                    foreach ($this->helper->getFields() as $field) {
                        if (isset($data[$field])) {
                            $currentItem->setData($field, $data[$field]);
                        }
                    }
                    if (!empty($data[ProductOptions::LINKED_ATTRIBUTE_IDS])) {
                        $linkedAttributes = implode(',', $data[ProductOptions::LINKED_ATTRIBUTE_IDS]);
                        $currentItem->setData(ProductOptions::LINKED_ATTRIBUTE_IDS, $linkedAttributes);
                    }
                    if (isset($data[ProductOptions::DISPLAY_ATTRIBUTES_IN_MATRIX])) {
                        $currentItem->setData(
                            ProductOptions::DISPLAY_ATTRIBUTES_IN_MATRIX,
                            $data[ProductOptions::DISPLAY_ATTRIBUTES_IN_MATRIX]
                        );
                    }
                    $this->productOptionsRepository->save($currentItem);
                } else {
                    $this->productOptionsRepository->deleteByProductId($productId);
                }
            }

        }
    }
}
