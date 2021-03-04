<?php

namespace Elevate\Delivery\Controller\Adminhtml\DeliveryProductImport;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\ScopeInterface;


class Save extends \Magento\Backend\App\Action
{

    protected $fileSystem;

    protected $uploaderFactory;

    protected $request;

    protected $adapterFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        AdapterFactory $adapterFactory

    ) {
        parent::__construct($context);
        $this->fileSystem = $fileSystem;
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
        $this->adapterFactory = $adapterFactory;
        $this->uploaderFactory = $uploaderFactory;
    }

    public function execute()
    {

        if ( (isset($_FILES['importdata']['name'])) && ($_FILES['importdata']['name'] != '') )
        {
            try
            {
                $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'importdata']);
                $uploaderFactory->setAllowedExtensions(['csv', 'xls']);
                $uploaderFactory->setAllowRenameFiles(true);
                $uploaderFactory->setFilesDispersion(true);

                $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                $destinationPath = $mediaDirectory->getAbsolutePath('Elevate_Delivery_IMPORTDATA');

                $result = $uploaderFactory->save($destinationPath);

                if (!$result)
                {
                    throw new LocalizedException
                    (
                        __('File cannot be saved to path: $1', $destinationPath)
                    );

                }
                else
                {
                    $imagePath = 'Elevate_Delivery_IMPORTDATA'.$result['file'];

                    $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);

                    $destinationfilePath = $mediaDirectory->getAbsolutePath($imagePath);

                    /* file read operation */

                    $f_object = fopen($destinationfilePath, "r");

                    $column = fgetcsv($f_object);

                    // column name must be same as the Sample file name

                    if($f_object)
                    {
                        if( ($column[0] == 'Col_name_1') && ($column[1] == 'Col_name_2') && ($column[2] == 'Col_name_3') && ($column[3] == 'Col_name_4') && ($column[4] == 'Col_name_5') )
                        {

                            $count = 0;

                            while (($columns = fgetcsv($f_object)) !== FALSE)
                            {

                                $rowData = $this->_objectManager->create('Dolphin\Storelocator\Model\Storelocator');

                                if($columns[0] != 'Col_name_1')// unique Name like Primary key
                                {
                                    $count++;

                                    /// here this are all the Getter Setter Method which are call to set value
                                    // the auto increment column name not used to set value

                                    $rowData->setCol_name_1($columns[1]);

                                    $rowData->setCol_name_2($columns[2]);

                                    $rowData->setCol_name_3($columns[3]);

                                    $rowData->setCol_name_4($columns[4]);

                                    $rowData->setCol_name_5($columns[5]);

                                    $rowData->save();

                                }

                            }

                            $this->messageManager->addSuccess(__('A total of %1 record(s) have been Added.', $count));
                            $this->_redirect('elevate_delivery/importdata/index');
                        }
                        else
                        {
                            $this->messageManager->addError(__("invalid Formated File"));
                            $this->_redirect('elevate_delivery/deliveryproductimport/importdata');
                        }

                    }
                    else
                    {
                        $this->messageManager->addError(__("File hase been empty"));
                        $this->_redirect('elevate_delivery/deliveryproductimport/importdata');
                    }

                }

            }
            catch (\Exception $e)
            {
                $this->messageManager->addError(__($e->getMessage()));
                $this->_redirect('importdata/deliveryproductimport/importdata');
            }

        }
        else
        {
            $this->messageManager->addError(__("Please try again."));
            $this->_redirect('importdata/deliveryproductimport/importdata');
        }
    }
}
