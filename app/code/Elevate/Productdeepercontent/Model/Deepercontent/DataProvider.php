<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Model\Deepercontent;

use Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    protected $loadedData;
    protected $dataPersistor;

   /**
    * Store manager
    *
    * @var \Magento\Store\Model\StoreManagerInterface
    */
    protected $storeManager;


    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
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
            $itemData = $model->getData();

            if (isset($itemData['deepercontent_image'])) {
                $imageName = explode('/', $itemData['deepercontent_image']);
                $itemData['deepercontent_image'] = [
                    [
                        'name' => $imageName[0],
                        'previewType' => 'image',
                        'url' => $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'elevate/proddeepecontent/image/' . $itemData['deepercontent_image'],
                    ],
                ];
            } else {
                $itemData['deepercontent_image'] = null;
            }

            $this->loadedData[$model->getId()] = $itemData;

        }
        $data = $this->dataPersistor->get('elevate_productdeepercontent_deepercontent');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('elevate_productdeepercontent_deepercontent');
        }



        return $this->loadedData;
    }


}

