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
namespace Aheadworks\Giftcard\Ui\DataProvider\Giftcard;

use Aheadworks\Giftcard\Model\ResourceModel\Giftcard\CollectionFactory;
use Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Collection;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;

/**
 * Class FormDataProvider
 *
 */
class FormDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var AuthSession
     */
    private $authSession;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @var array
     */
    private $dateFields = ['delivery_date', 'expire_at', 'created_at'];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param AuthSession $authSession
     * @param StoreManager $storeManager
     * @param TimezoneInterface $localeDate
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        AuthSession $authSession,
        StoreManager $storeManager,
        TimezoneInterface $localeDate,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
        $this->authSession = $authSession;
        $this->storeManager = $storeManager;
        $this->localeDate = $localeDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get('aw_giftcard_giftcard');
        if (!empty($dataFromForm)) {
            $data[$dataFromForm['id']] = $dataFromForm;
            $this->dataPersistor->clear('aw_giftcard_giftcard');
        } else {
            $id = $this->request->getParam($this->getRequestFieldName());
            if ($id) {
                $giftcards = $this->getCollection()->addFieldToFilter('id', $id)->getItems();
                /** @var \Aheadworks\Giftcard\Model\Giftcard $giftcard */
                foreach ($giftcards as $giftcard) {
                    if ($id == $giftcard->getId()) {
                        $data[$id] = $giftcard->getData();
                    }
                }
                foreach ($this->dateFields as $dateField) {
                    $this->convertDate($data, $id, $dateField);
                }
            } else {
                $data[$id] = [
                    'sender_name' => $this->authSession->getUser()->getFirstname(),
                    'sender_email' => $this->authSession->getUser()->getEmail()
                ];
            }

            if (!isset($data[$id]['delivery_date_timezone'])
                || (isset($data[$id]['delivery_date_timezone']) && !$data[$id]['delivery_date_timezone'])
            ) {
                $data[$id]['delivery_date_timezone'] = $this->localeDate->getConfigTimezone(
                    ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getDefaultStoreView()->getCode()
                );
            }
        }

        return $data;
    }

    /**
     * Convert date
     *
     * @param array $data
     * @param string $id
     * @param string $dateField
     */
    private function convertDate(&$data, $id, $dateField) {
        if (isset($data[$id][$dateField]) && $data[$id][$dateField]) {
            $deliveryDate = $this->localeDate->date(strtotime($data[$id][$dateField]));
            $data[$id][$dateField] = $deliveryDate->format(StdlibDateTime::DATETIME_PHP_FORMAT);
        }
    }
}
