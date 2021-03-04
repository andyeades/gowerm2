<?php /** * Copyright © 2013-2017 Magento, Inc. All rights reserved. * See COPYING.txt for license details. */

namespace Elevate\ProductIcons\Ui\DataProvider\Product;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Elevate\ProductIcons\Model\ResourceModel\Producticons\CollectionFactory;
use Elevate\ProductIcons\Model\ResourceModel\Producticons\Collection;
use Elevate\ProductIcons\Model\Producticons;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/** * Class CustomDataProvider * * @method Collection getCollection */
class CustomDataProvider extends AbstractDataProvider {



  /** * @var CollectionFactory */
  protected $collectionFactory;

  /** * @var RequestInterface */
  protected $request;

  /**
   * @var PoolInterface
   */
  private $pool;

  /**
   * @param string $name
   * @param string $primaryFieldName
   * @param string $requestFieldName
   * @param CollectionFactory $collectionFactory
   * @param RequestInterface $request
   * @param PoolInterface $pool
   * @param array $meta
   * @param array $data
   */
  public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    CollectionFactory $collectionFactory,
    RequestInterface $request,
    PoolInterface $pool,
    array $meta = [],
    array $data = [])
  {
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    $this->collectionFactory = $collectionFactory;
    $this->collection = $this->collectionFactory->create();
    $this->request = $request;
    $this->pool = $pool;
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    $this->getCollection();

    $arrItems = [
      'totalRecords' => $this->getCollection()->getSize(),
      'items'        => [],
    ];

    foreach ($this->getCollection() as $item) {
      $arrItems['items'][] = $item->toArray([]);
    }

    return $arrItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getMeta()
  {
    $meta = parent::getMeta();
    /** @var ModifierInterface $modifier */
    foreach ($this->pool->getModifiersInstances() as $modifier) {
      $meta = $modifier->modifyMeta($meta);
    }
    return $meta;
  }
}