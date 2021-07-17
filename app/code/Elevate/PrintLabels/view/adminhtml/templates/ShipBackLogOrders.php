<?php

use \Magento\Framework\App\Bootstrap;

include('app/bootstrap.php');

$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$orderInterface = $objectManager->get('\Magento\Sales\Api\Data\OrderInterface');

//Use this if you have orderId
//$orderId = "100"; //Order Id
//$order = $orderInterface->load($orderId);

$orders = array('000009468','000009469','000009470','000009471','000009472','000009473','000009481','000009488','000009490','000009492','000009493','000009494','000009495','000009496','000009497','000009499','000009500','000009501','000009502','000009503','000009504','000009505','000009506','000009507','000009508','000009509','000009510','000009512','000009513','000009514','000009516','000009517','000009518','000009520','000009521','000009523','000009524','000009525','000009526','000009527','000009528','000009529','000009530','000009531','000009532','000009533','000009534','000009535','000009536','000009538','000009539','000009540','000009541','000009542','000009543','000009545','000009546','000009547','000009548','000009549','000009550','000009552','000009553','000009554','000009555','000009556','000009557','000009558','000009559','000009560','000009561','000009562','000009563','000009565','000009566','000009567','000009568','000009573','000009574','000009575','000009576','000009578','000009579','000009580','000009581','000009582','000009584','000009585','000009586','000009587','000009588','000009589','000009590','000009591','000009592','000009593','000009594','000009595','000009596','000009597','000009598','000009600','000009601','000009603','000009604','000009605','000009606','000009607','000009608','000009610','000009611','000009612','000009613','000009614','000009615','000009616','000009617','000009618','000009619','000009620','000009621','000009622','000009623','000009624','000009625','000009626','000009627','000009628','000009629','000009630','000009631','000009632','000009633','000009634','000009635','000009636','000009637','000009638','000009639','000009640','000009641','000009642','000009643','000009644','000009645','000009646','000009647','000009648','000009649','000009650','000009651','000009652','000009653','000009654','000009655','000009656','000009657','000009658','000009659','000009660','000009661','000009662','000009663','000009664','000009665','000009666','000009667','000009668','000009669','000009670','000009671','000009672','000009673','000009674','000009675','000009677','000009678','000009679','000009680','000009681','000009682','000009683','000009684','000009685','000009686','000009687','000009688','000009689','000009715','000009716','000009717','000009718','000009721','000009722','000009723','000009725','000009726','000009727','000009728','000009729','000009730','000009731','000009732','000009733','000009737','000009741','000009742','000009743','000009744','000009745');

foreach ($orders as $order_id) {


    $order = $objectManager->create('Magento\Sales\Model\Order')->loadByAttribute('increment_id', $order_id);

    if ($order->canShip()) {
        // Initialize the order shipment object
        $convertOrder = $objectManager->create('Magento\Sales\Model\Convert\Order');
        $shipment = $convertOrder->toShipment($order);
        // Loop through order items
        foreach ($order->getAllItems() as $orderItem) {
            // Check if order item has qty to ship or is virtual
            if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }
            $qtyShipped = $orderItem->getQtyToShip();
            // Create shipment item with qty
            $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
            // Add shipment item to shipment
            $shipment->addItem($shipmentItem);
        }

        // Register shipment
        $shipment->register();
        $shipment->getOrder()->setIsInProcess(true);

        try {
            // Save created shipment and order
            $shipment->save();
            $shipment->getOrder()->save();

            // Send email
            //$objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
            //    ->notify($shipment);
            //$shipment->save();

        } catch(\Exception $e) {
            echo "$order_id - "."Shipment Not Created" . $e->getMessage();
        }

        echo "Shipment Succesfully Generated for order: #" . $order_id."\n";
    } else {
        echo "Shipment Not Created Because It's already created or something went wrong\n";
    }
}

