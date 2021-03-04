<?php

namespace Elevate\Shell\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Bsales extends Command
{
    private $objectManager;
    protected $log_file = 'sale_flag.log';
    protected $pageSize = 1000;
    protected $fileDelimiter = ',';
    protected $dryrun = false;

    protected $resourceConnection;
    protected $read;
    protected $write;
    protected $table;
    protected $fileHandle;
    protected $boostArray = [];
    protected $_orderCollectionFactory;
    protected $_shipmentCollectionFactory;
    protected $orderRepository;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        \Magento\Framework\App\State $state,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Shipment\CollectionFactory $shipmentCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    )
    {
        $this->objectManager = $objectmanager;
        $this->productRepository = $productrepository;
        $this->state = $state;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_shipmentCollectionFactory = $shipmentCollectionFactory;
        $this->orderRepository = $orderRepository;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('elevate:shell:send_despatch_emails')->setDescription('Send Despatch Emails');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $shipmentCollection = $this->_getShipmentCollection();
        foreach($shipmentCollection as $shipment){

            print_r($shipment->getData());
            exit;
        }

    }
    protected function _getShipmentCollection() {


        $productCollection = $this->_shipmentCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        //$productCollection->setStoreId(0);
        //$productCollection->addAttributeToFilter('price', array('eq' => '0'));
        $productCollection->load();


        $productCollection->setOrder('entity_id', 'DESC');
        $productCollection->setPageSize($this->pageSize);

        $productCollection->setCurPage(1);  // first page (means limit 0,10)
        return $productCollection;
    }
    private function sendEmail($message){
        //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
        //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
        //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
    }
}
