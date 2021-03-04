<?php
declare(strict_types=1);
/**
 * ProductProvider
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class ProductProvider extends AbstractSimpleObject
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ProductProvider constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($data);
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $id
     * @return mixed|null|ProductInterface
     * @throws NoSuchEntityException
     */
    public function getProductById($id)
    {
        if (!$this->_get($id)) {
            $product = $this->productRepository
                ->getById($id, false, $this->storeManager->getStore()->getId());
            $this->setData($id, $product);
        }
        return $this->_get($id);
    }
}
