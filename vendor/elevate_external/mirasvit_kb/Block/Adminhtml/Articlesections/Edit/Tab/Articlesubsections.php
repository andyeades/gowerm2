<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */

namespace Mirasvit\Kb\Block\Adminhtml\Articlesections\Edit\Tab;

use Mirasvit\Kb\Model\ArticlesubsectionsFactory;

class Articlesubsections extends \Magento\Backend\Block\Widget\Grid\Extended {
    /**
     * @var '\Mirasvit\Kb\Model\GridFactory'
     */
    protected $articlesubsectionsFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Mirasvit\Kb\Model\ArticlesectionsFactory $articlesubsectionsFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->articlesubsectionsFactory = $articlesubsectionsFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('articlesubsections_tab_grid');
        $this->setDefaultSort('articlesubsection_id');
        $this->setUseAjax(true);
    }

    /**
     */
    protected function _prepareCollection()
    {
        $id = $this->getRequest()->getParam('id');

        $collection = $this->articlesubsectionsFactory->create()->getCollection()->addFieldToFilter('parentarticlesection_id',['eq' => $id]);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'articlesubsection_id',
            [
                'header' => __('Article Sub Section Id'),
                'sortable' => true,
                'index' => 'articlesubsection_id',
                'header_css_class' => 'col-id',
                'align' => 'center',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'asecsub_name',
            [
                'header' => __('Name'),
                'index' => 'asecsub_name'
            ]
        );
        $this->addColumn(
            'asecsub_is_active',
            [
                'header' => __('Enabled'),
                'index' => 'asecsub_is_active'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/articlesubsectionsgrid', ['_current' => true]);
    }


}
