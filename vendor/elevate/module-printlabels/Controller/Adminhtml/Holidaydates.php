<?php


namespace Elevate\PrintLabels\Controller\Adminhtml;

abstract class Holidaydates extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Elevate_PrintLabels::title';
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('PrintLabels'), __('PrintLabels'))
            ->addBreadcrumb(__('Holidaydates'), __('Holidaydates'));
        return $resultPage;
    }
}
