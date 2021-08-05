<?php
namespace Elevate\Delivery\Observer;

use Magento\Framework\Event\ObserverInterface;

class SetCustomCheckoutAttributes implements ObserverInterface
{

    /* @var \Magento\GiftMessage\Model\GiftMessageManager */
    protected $giftMessageManager;

    /* @var \Magento\GiftMessage\Model\MessageFactory */
    protected $giftMessageFactory;

    /* @var \Magento\GiftMessage\Model\ResourceModel\Message $resource */
    protected $giftMessageResource;




    /**
     * Constructor
     *
     */
    public function __construct(
        \Magento\GiftMessage\Model\GiftMessageManager $giftMessageManager,
        \Magento\GiftMessage\Model\MessageFactory $giftMessageFactory,
        \Magento\GiftMessage\Model\ResourceModel\Message $giftMessageResource
    ) {
        $this->giftMessageManager = $giftMessageManager;
        $this->giftMessageFactory = $giftMessageFactory;
        $this->giftMessageResource = $giftMessageResource;
    }


    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');


        // This doesn't Affect Multishipping Checkout
        // This is necessary for the Standard Checkout - As Otherwise it doesn't capture the Gift Message we are using, and add it to the quote properly.



        $gift_message = $quote->getShippingAddress()->getEvGiftmessagemessage();

        if ($gift_message) {
            $gift_message_obj = $this->giftMessageFactory->create();


            $gift_message_obj->setMessage($gift_message);

            $this->giftMessageResource->save($gift_message_obj);

            $gift_message_id = $gift_message_obj->getId();

            $quote->setGiftMessageId($gift_message_id);
        }

        return $this;
    }
}
