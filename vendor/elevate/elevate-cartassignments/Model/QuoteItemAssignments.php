<?php


namespace Elevate\CartAssignments\Model;

use Magento\Framework\Api\DataObjectHelper;
use Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterfaceFactory;
use Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface;

/**
 * Class QuoteItemAssignments
 *
 * @package Elevate\CartAssignments\Model
 */
class QuoteItemAssignments extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_cartassignments_quoteitemassignments';
    protected $quoteitemassignmentsDataFactory;
protected $_cartAssignmentsFactory;
 //   protected $_cartAssignmentsModal;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param QuoteItemAssignmentsInterfaceFactory $quoteitemassignmentsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments $resource
     * @param \Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        QuoteItemAssignmentsInterfaceFactory $quoteitemassignmentsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments $resource,
        \Elevate\CartAssignments\Model\QuoteItemAssignmentsFactory $cartAssignmentsFactory,
        //\Elevate\CartAssignments\Model\QuoteItemAssignments $cartAssignmentsModal,
        \Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments\Collection $resourceCollection,
        array $data = []
    ) {
        $this->quoteitemassignmentsDataFactory = $quoteitemassignmentsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->_cartAssignmentsFactory = $cartAssignmentsFactory;
       // $this->_cartAssignmentsModal = $cartAssignmentsModal;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve quoteitemassignments model with quoteitemassignments data
     * @return QuoteItemAssignmentsInterface
     */
    public function getDataModel()
    {
        $quoteitemassignmentsData = $this->getData();

        $quoteitemassignmentsDataObject = $this->quoteitemassignmentsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $quoteitemassignmentsDataObject,
            $quoteitemassignmentsData,
            QuoteItemAssignmentsInterface::class
        );

        return $quoteitemassignmentsDataObject;
    }
    public function loadAssigned($quoteItemId)
    {
        return $this->loadByAttribute('linked_quote_item_id', $quoteItemId);
    }

    public function loadAssignedChildren($quoteItemId)
    {
        echo "QUOTE_ITEM::".$quoteItemId;
        $collection = $this->_cartAssignmentsFactory->create()->getCollection()
                                                    ->addFieldToFilter('parent_quote_item_id', $quoteItemId);

        // $this->load($value, $attribute);
        return $collection;
        //return $this->loadByAttribute('parent_quote_item_id', $quoteItemId);
    }

    public function loadByAttribute($attribute, $value)
    {
        $this->load($value, $attribute);
        return $this;
    }
    public function assignProduct(
        $quote_item_id_parent,
        $linked_quote_item_id,
        $quote_id,
        $addon_id,
        $addon_location,
        $add_qty
    )
    {

        if($add_qty<1){
            $add_qty = 1;
        }
        ///check if we have a current assignment
        $assignment_id = false;
        if (is_numeric($quote_item_id_parent)) {//the parameter name may be different


            //$multibox_id = Mage::getStoreConfig('elevate_assignments/general/multibox_id');
            $multibox_id = 999;

            if($addon_id == $multibox_id) {
                $collection = $this->_cartAssignmentsFactory->create()->getCollection()
                                  ->addFieldToFilter('parent_quote_item_id', $quote_item_id_parent)
                                  ->addFieldToFilter('linked_quote_item_id', $linked_quote_item_id)
                                  ->addFieldToFilter('addon_id', $addon_id)
                                  ->addFieldToFilter('location', $addon_location);
            }
            else{

                $collection = $this->_cartAssignmentsFactory->create()->getCollection()
                                  ->addFieldToFilter('parent_quote_item_id', $quote_item_id_parent)
                          ->addFieldToFilter('linked_quote_item_id', $linked_quote_item_id)
                                  ->addFieldToFilter('addon_id', $addon_id)
                    ->addFieldToFilter('location', $addon_location);

            }


            //->addAttributeToFilter('location', $addon_location);
            foreach($collection as $data) {

                $assignment_id = $data['quoteitemassignments_id'];

            }

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $quoteItemAssignmentsModel = $objectManager->create('\Elevate\CartAssignments\Model\QuoteItemAssignments')->load($assignment_id);

          //  $quoteItemAssignmentsModel = $this->_cartAssignmentsModal->load($assignment_id);

    if (property_exists($quoteItemAssignmentsModel, 'qty'))
        $add_qty = $quoteItemAssignmentsModel->qty + $add_qty;

        }

        if(!$assignment_id) {//change this to cant find existing -then load this model

            //$quoteItemAssignmentsModel = $this->resource;

        }


        // echo $assignment_id;

        //           $data['enable_image_overlay_banner'] = $this->getNewImage('enable_image_overlay_banner', $data);
//echo "<br>".$linked_quote_item_id."|".$quote_item_id_parent."|".$quote_id."|".$addon_id."|".$addon_location."|1|".$add_qty."<br>";
        $quoteItemAssignmentsModel->setData('linked_quote_item_id', $linked_quote_item_id);
        $quoteItemAssignmentsModel->setData('parent_quote_item_id', $quote_item_id_parent);
        $quoteItemAssignmentsModel->setData('quote_id', $quote_id);
        $quoteItemAssignmentsModel->setData('addon_id', $addon_id);
        $quoteItemAssignmentsModel->setData('location', $addon_location);


        $quoteItemAssignmentsModel->setData('template_item_id', 1);//default - needs lookup
        $quoteItemAssignmentsModel->setData('qty', $add_qty);
        $return[$quote_item_id_parent] = $add_qty;

        try {
            $insertId = $quoteItemAssignmentsModel->save()->getId();
           // exit;
          //  Mage::getSingleton('adminhtml/session')->addSuccess('Saved');
            //redirect to grid.
            return $return;

            //  exit;
        } catch(Exception $e) {
           //Mage::log($e->getMessage());
            print_r($e->getMessage());
            //exit;
        }

    }
}

