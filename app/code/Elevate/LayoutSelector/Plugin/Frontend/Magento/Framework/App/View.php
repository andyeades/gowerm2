<?php

namespace Elevate\LayoutSelector\Plugin\Frontend\Magento\Framework\App;

use Magento\Framework\Exception\NoSuchEntityException;

class View {
    /**
     * @param \Magento\Framework\App\View $subject
     * @param \Magento\Framework\View\Result\Page $resultPage
     *
     * @return array
     */
    //loadLayout($handles = null, $generateBlocks = true, $generateXml = true, $addActionHandles = true)
    public function __construct(
        \Magento\Framework\View\Result\Page $resultPage
    ) {
        $this->resultPage = $resultPage;
    }

    public function beforeGenerateLayoutXml(
        \Magento\Framework\App\View $subject
    ) {
        try {


            //echo 'HERE';
            //exit;
            // Pull this from Admin?

           // $subject->getLayout()->getUpdate()->removeHandle('catalogsearch_result_index');
            //$pageConfig = $this->resultPage->getConfig();
            //$pageConfig->setPageLayout('2columns-right');
            //$subject->getLayout()->getUpdate()->addHandle('catalogsearch_result_index_gowercottage');

        } catch(NoSuchEntityException $noSuchEntityException) {
            // Do no thing
        }

        return [
            $subject
        ];
    }

}

