<?php

namespace SecureTrading\Trust\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\App\State;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use SecureTrading\Trust\Helper\Data;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var State
     */
    private $state;
    /**
     * @var StatusFactory
     */
    protected $statusFactory;

    /**
     * UpgradeData constructor.
     * @param StatusFactory $statusFactory
     * @param State $state
     */
    public function __construct(
        StatusFactory $statusFactory,
        State $state
    )
    {
        $this->state         = $state;
        $this->statusFactory = $statusFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->createSecureTradingOrderStatus();
        }
        $setup->endSetup();
    }

    /**
     * @throws \Exception
     */
    private function createSecureTradingOrderStatus()
    {
        $this->state->emulateAreaCode(
            'global',
            function () {
                /** @var Status $status */
                $status = $this->statusFactory->create();
                $status->load(Data::ORDER_STATUS);
                $status->setData([
                    'status' => Data::ORDER_STATUS,
                    'label' => Data::ORDER_STATUS_LABEL,
                ]);
                $status->save();
                $status->assignState(Order::STATE_NEW, false, true);
            }
        );
    }
}
