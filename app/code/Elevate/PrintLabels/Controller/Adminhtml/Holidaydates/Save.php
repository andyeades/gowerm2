<?php


namespace Elevate\PrintLabels\Controller\Adminhtml\Holidaydates;

use Elevate\PrintLabels\Api\Data\HolidaydatesInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    protected $holidaydatesDataFactory;

    /* @var $holidaydatesRepository \Elevate\PrintLabels\Api\HolidaydatesRepositoryInterface */
    protected $holidaydatesRepository;
    protected $holidaydatesFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param HolidaydatesInterfaceFactory $holidaydatesDataFactory
     * @param \Elevate\PrintLabels\Model\HolidaydatesFactory $holidaydatesFactory
     *  $holidaydatesRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        HolidaydatesInterfaceFactory $holidaydatesDataFactory,
        \Elevate\PrintLabels\Model\HolidaydatesFactory $holidaydatesFactory,
        \Elevate\PrintLabels\Api\HolidaydatesRepositoryInterface $holidaydatesRepository
    ) {
        $this->holidaydatesDataFactory = $holidaydatesDataFactory;
        $this->holidaydatesRepository = $holidaydatesRepository;
        $this->holidaydatesFactory = $holidaydatesFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('printlabelsholidaydates_id');

            if (empty($id)) {
                $holidaydates = $this->holidaydatesRepository->create();

                // TODO:: See if Ui Component is fixed. Shouldn't need to do this for it >_<
                //Ridiculous because of the stupid datetimepicker?

                $start_date = $data['start_date'];
                $end_date = $data['end_date'];

                $strtotime_start_date = strtotime($start_date);

                $start_date_output = date('Y-m-d H:i:s', $strtotime_start_date);

                $strtotime_end_date = strtotime($end_date);

                $end_date_output = date('Y-m-d H:i:s', $strtotime_end_date);


                //$model->setData($data);
                $holidaydates->setStartDate($start_date_output);
                $holidaydates->setEndDate($end_date_output);
                $holidaydates->setPrintlabelsholidaytitle($data['printlabelsholidaytitle']);
            } else {

                $holidaydates = $this->holidaydatesRepository->getById($id);


                if (!$holidaydates->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This Holiday Date no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }

                // TODO:: See if Ui Component is fixed. Shouldn't need to do this for it >_<
                //Ridiculous because of the stupid datetimepicker?

                $start_date = $data['start_date'];
                $end_date = $data['end_date'];

                $strtotime_start_date = strtotime($start_date);

                $start_date_output = date('Y-m-d H:i:s', $strtotime_start_date);

                $strtotime_end_date = strtotime($end_date);

                $end_date_output = date('Y-m-d H:i:s', $strtotime_end_date);

                $data['start_date'] = $start_date_output;
                $data['end_date'] = $end_date_output;



                $holidaydates->setStartDate($start_date_output);
                $holidaydates->setEndDate($end_date_output);
                $holidaydates->setPrintlabelsholidaytitle($data['printlabelsholidaytitle']);
            }






            // Ridiculous



            try {
                $holidaydates->save();
                $this->messageManager->addSuccessMessage(__('You saved the Holiday Date.'));
                $this->dataPersistor->clear('elevate_printlabels_holidaydates');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['printlabelsholidaydates_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Holiday Date.'));
            }

            $this->dataPersistor->set('elevate_delivery_holidaydates', $data);
            return $resultRedirect->setPath('*/*/edit', ['printlabelsholidaydates_id' => $this->getRequest()->getParam('printlabelsholidaydates_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
