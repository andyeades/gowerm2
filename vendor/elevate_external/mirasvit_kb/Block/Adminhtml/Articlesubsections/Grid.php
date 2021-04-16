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

namespace Mirasvit\Kb\Block\Adminhtml\Articlesubsections;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {
    /**
     * @var \Mirasvit\Kb\Model\ArticlesubsectionsFactory
     */
    protected $articlesubsectionsFactory;

    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    protected $kbData;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $backendHelper;

    /**
     * @param \Mirasvit\Kb\Model\ArticlesubsectionsFactory $articlesubsectionsFactory
     * @param \Mirasvit\Kb\Helper\Data                  $kbData
     * @param \Magento\Backend\Block\Widget\Context     $context
     * @param \Magento\Backend\Helper\Data              $backendHelper
     * @param array                                     $data
     */
    public function __construct(
        \Mirasvit\Kb\Model\ArticlesubsectionsFactory $articlesubsectionsFactory,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    ) {
        $this->articlesubsectionsFactory = $articlesubsectionsFactory;
        $this->kbData = $kbData;
        $this->context = $context;
        $this->backendHelper = $backendHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     *
     */
    protected function _construct() {
        parent::_construct();

        $this->setData('id', 'grid')->setDefaultSort('articlesubsection_id')->setDefaultDir('DESC')->setSaveParametersInSession(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection() {
        $collection = $this->articlesubsectionsFactory->create()->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns() {
        $this->addColumn(
            'articlesubsection_id', [
            'header'       => __('ID'),
            'index'        => 'articlesubsection_id',
            'filter_index' => 'main_table.articlesubsection_id',
        ]
        );
        $this->addColumn(
            'parentarticlesection_id', array(
                                'header'       => __('Parent Article'),
                                'name'         => 'parentarticlesection_id',
                                'index'        => 'parentarticlesection_id',
                                'filter_index' => 'main_table.parentarticlesection_id',
                            )
        );
        $this->addColumn(
            'asecsub_name', array(
                           'header'       => __('Name'),
                           'index'        => 'asecsub_name',
                           'name'         => 'asecsub_name',
                           'filter_index' => 'main_table.asecsub_name',
                       )
        );
        $this->addColumn(
            'asecsub_is_active', array(
                                'header'       => __('Active'),
                                'name'         => 'asecsub_is_active',
                                'index'        => 'asecsub_is_active',
                                'filter_index' => 'main_table.asecsub_is_active',
                                'type'         => 'options',
                                'options'      => array(
                                    0 => $this->__('No'),
                                    1 => $this->__('Yes')
                                ),
                            )
        );
        $this->addColumn(
            'asecsub_position', array(
                               'header'       => __('Position'),
                               'name'         => 'asecsub_position',
                               'index'        => 'asecsub_position',
                               'filter_index' => 'main_table.asecsub_position',
                           )
        );

        $this->addColumn(
            'asecsub_created_at', array(
                                 'header'       => __('Created At'),
                                 'name'         => 'asecsub_created_at',
                                 'index'        => 'asecsub_created_at',
                                 'filter_index' => 'main_table.asecsub_created_at',
                                 'type'         => 'date',
                             )
        );
        $this->addColumn(
            'asecsub_updated_at', array(
                                 'header'       => __('Updated At'),
                                 'name'         => 'asecsub_updated_at',
                                 'index'        => 'asecsub_updated_at',
                                 'filter_index' => 'main_table.asecsub_updated_at',
                                 'type'         => 'date',
                             )
        );
        /*
        $this->addColumn(
            'asecsub_sku_list', array(
                               'header'       => __('Sku List'),
                               'name'         => 'asecsub_sku_list',
                               'index'        => 'asecsub_sku_list',
                               'filter_index' => 'main_table.asecsub_sku_list',
                               'type'         => 'text',
                           )
        );
*/
        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('articlesubsection_id');
        $this->getMassactionBlock()->setFormFieldName('articlesubsection_id');
        $this->getMassactionBlock()->addItem(
            'delete', [
            'label'   => __('Delete'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => __('Are you sure?'),
        ]
        );

        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

    /**
     * Add page loader. Generate list of grid buttons.
     *
     * @return string
     */
    public function getMainButtonsHtml() {
        $html = '
            <div data-role="spinner" class="admin__data-grid-loading-mask" data-bind="visible: window.loading">
                <div class="spinner">
                    <span></span><span></span><span></span><span></span>
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>

            <script>
                require(["jquery"],function($) {
                    $(function(){
                        setTimeout(hideSpinner, 500);
                    });
                });
                function hideSpinner() {
                    jQuery(\'[data-role="spinner"]\').hide();
                }
            </script>
        ';

        return $html . parent::getMainButtonsHtml();
    }

    /************************/
}
