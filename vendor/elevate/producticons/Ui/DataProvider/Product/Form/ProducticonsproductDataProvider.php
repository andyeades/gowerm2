<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\ProductIcons\Ui\DataProvider\Product\Form;

use Elevate\ProductIcons\Model\ResourceModel\Producticons\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;


/**
 * DataProvider for Edit Form
 *
 * @api
 * @since 101.0.0
 */
class ProducticonsproductDataProvider extends AbstractDataProvider
{
  /**Elevate\ProductIcons\Ui\DataProvider\Product\Form\ProducticonsproductDataProvider
   * @var array
   */
  protected $loadedData;
  protected $collection;

  protected $storeManager;
  protected $dataPersistor;

  /**
   * @param string $name
   * @param string $primaryFieldName
   * @param string $requestFieldName
   * @param CollectionFactory $collectionFactory
   * @param DataPersistorInterface $dataPersistor
   * @param StoreManagerInterface $storeManager,
   * @param array $meta
   * @param array $data
   */
  public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    CollectionFactory $collectionFactory,
    DataPersistorInterface $dataPersistor,
    StoreManagerInterface $storeManager,
    array $meta = [],
    array $data = []
  ) {
    $this->collection = $collectionFactory->create();
    $this->dataPersistor = $dataPersistor;
    $this->storeManager = $storeManager;
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
  }

  /**
   * Get data
   *
   * @return array
   */
  public function getData()
  {
    if (isset($this->loadedData)) {
      return $this->loadedData;
    }
    $items = $this->collection->getItems();
    foreach ($items as $model) {
      $this->loadedData[$model->getIconId()] = $model->getData();
      if ($model->getIconUrl()) {
        $temp_array['icon_url'][0]['name'] = $model->getIconUrl();
        $temp_array['icon_url'][0]['url'] = $this->getMediaUrl().$model->getIconUrl();
        $fullData = $this->loadedData;
        $this->loadedData[$model->getIconId()] = array_merge($fullData[$model->getIconId()], $temp_array);
      }
    }
    $data = $this->dataPersistor->get('elevate_producticons');


    if (!empty($data)) {
      $model = $this->collection->getNewEmptyItem();
      $model->setData($data);
      $this->loadedData[$model->getIconId()] = $model->getData();
      $this->dataPersistor->clear('elevate_producticons');
    }

    return $this->loadedData;
  }

  /**
   * {@inheritdoc}
   * @since 101.0.0
   */
  public function getMeta()
  {
    $meta = parent::getMeta();

    return $meta;
  }


  public function getMediaUrl()
  {
    $mediaUrl = $this->storeManager->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'elevate/tmp/producticons/';
    return $mediaUrl;
  }
}
