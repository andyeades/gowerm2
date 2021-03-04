<?php

namespace Elevate\LayoutSelector\Plugin\Frontend\Magento\Framework\View\Result;

use Magento\Framework\Exception\NoSuchEntityException;

class Page {
    /**
     * @param \Magento\Framework\View\Result\Page $subject
     *
     * @return array
     */
    public function beforeInitLayout(
        \Magento\Framework\View\Result\Page $subject
    ) {
        try {
            //echo 'HERE';
            //exit;
            // Pull this from Admin?

            //$subject->addHandle('catalogsearch_result_index_gowercottage');

        } catch(NoSuchEntityException $noSuchEntityException) {
            // Do no thing
        }

        return [
            $subject
        ];
    }

}

