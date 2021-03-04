<?php
namespace Elevate\ProductIcons\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Elevate\ProductIcons\Model\Config\FileUploader\FileProcessor as IconImageProcessor;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Asset\Repository;

/**
 * Class Image
 */
class Image extends \Magento\Ui\Component\Listing\Columns\Column
{
  private $storeManager;

  /**
     * @var IconImageProcessor
     */
    protected $imageProcessor;

    /**
     * @var AssetRepository
     */
    protected $assetRepo;


    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param AssetRepository $assetRepo
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        AssetRepository $assetRepo,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
    }

    /**
     * @param array $items
     * @return array
     */
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
      if (isset($dataSource['data']['items'])) {
      $path = $this->storeManager->getStore()->getBaseUrl(
          \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ).'elevate/tmp/producticons/';
      $baseImage = $this->assetRepo->getUrl('Elevate_ProductIcons::images/dummy.png');
      foreach ($dataSource['data']['items'] as & $item) {
        if ($item['icon_url']) {
          $item['icon_url' . '_src'] = $path.$item['icon_url'];
          $item['icon_url' . '_alt'] = $item['icon_title'];
          $item['icon_url' . '_orig_src'] = $path.$item['icon_url'];
        } else {
          $item['icon_url' . '_src'] = $baseImage;
          $item['icon_url' . '_alt'] = 'Nothing';
          $item['icon_url' . '_orig_src'] = $baseImage;
        }
      }
    }


      return $dataSource;
    }
}
