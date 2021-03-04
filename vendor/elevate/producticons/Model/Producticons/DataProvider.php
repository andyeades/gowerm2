<?php
namespace Elevate\ProductIcons\Model\Producticons;


use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DataProvider
 * @package Elevate\ProductIcons\Model\Producticons
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
  /**
   * @var
   */
  protected $_loadedData;
  /**
   * @var \Magento\Framework\Registry
   */
  protected $_coreRegistry;

  /**
   * DataProvider constructor.
   *
   * @param null $name
   * @param null $primaryFieldName
   * @param null $requestFieldName
   * @param \Elevate\ProductIcons\Model\ResourceModel\Producticons\CollectionFactory $collectionFactory
   * @param \Magento\Framework\Registry                                      $registry
   * @param array                                                            $meta
   * @param array                                                            $data
   */
  public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    \Elevate\ProductIcons\Model\ResourceModel\Producticons\CollectionFactory $collectionFactory,
    \Magento\Framework\Registry $registry,
    StoreManagerInterface $storeManager,
    array $meta = [],
    array $data = []
  ) {
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    $this->collection = $collectionFactory->create();
    $this->_coreRegistry = $registry;
    $this->storeManager = $storeManager;
  }

  /**
   * @return array
   */
  public function getData() {
    if (isset($this->_loadedData)) {
      return $this->_loadedData;
    }
    $items = $this->collection->getItems();
    foreach($items as $item) {
      $this->_loadedData[$item->getId()] = $item->getData();
      if ($item->getIcon()) {
        $tmp_array['icon_url'][0]['name'] = $item->getIconUrl();
        $tmp_array['icon_url'][0]['url'] = $this->getMediaUrl().$item->getIconUrl();
        $fullData = $this->loadedData;
        $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $tmp_array);
      }

    }

    return $this->_loadedData;
  }

  /**
   * @return string
   */
  public function getMediaUrl()
  {
    $mediaUrl = $this->storeManager->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'elevate/tmp/producticons';
    return $mediaUrl;
  }
}