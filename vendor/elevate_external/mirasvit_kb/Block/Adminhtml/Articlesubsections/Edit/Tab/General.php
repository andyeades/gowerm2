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
namespace Mirasvit\Kb\Block\Adminhtml\Articlesubsections\Edit\Tab;

use Mirasvit\Kb\Model\Articlesubsections;

class General extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    private $context;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    private $kbData;
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    private $wysiwygConfig;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    private $formFactory;
    /**
     * @var \Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface
     */
    private $articlesubsectionsManagement;
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $backendUrl;

    /**
     * @var \Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface
     */
    protected $articlesubsectionsRepository;


    /**
     * General constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Mirasvit\Kb\Helper\Form\Article\Category $formCategoryHelper
     * @param \Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface $articlesubsectionsRepository
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Mirasvit\Kb\Helper\Form\Article\Category $formCategoryHelper,
        \Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface $articlesubsectionsRepository,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->objectManager          = $objectManager;
        $this->formCategoryHelper     = $formCategoryHelper;
        $this->articlesubsectionsRepository      = $articlesubsectionsRepository;
        $this->backendUrl             = $backendUrl;
        $this->kbData                 = $kbData;
        $this->formFactory            = $formFactory;
        $this->registry               = $registry;
        $this->context                = $context;
        $this->wysiwygConfig          = $wysiwygConfig;

        $this->articlesubsectionsManagement          = $this->getArticlesubsectionsMagagement();

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);
        /** @var \Mirasvit\Kb\Model\Articlesubsections $articlesubsections */
        $articlesubsections = $this->registry->registry('current_articlesubsection');

        $fieldset = $form->addFieldset('edit_fieldset', [
            'class'  => 'fieldset-wide field-article-form',
            'legend' => __('General Information'),
        ]);
        if ($articlesubsections->getId()) {
            $fieldset->addField('articlesubsection_id', 'hidden', [
                'name'  => 'articlesubsection_id',
                'value' => $articlesubsections->getId(),
            ]);
        }
        $fieldset->addField('parentarticlesection_id', 'select', [
            'label'    => __('Parent Article Section'),
            'required' => true,
            'name'     => 'parentarticlesection_id',
            'value'    => $articlesubsections->getParentarticlesectionId(),
            'values'   => $this->kbData->toArticleSectionOptionArray(),
        ]);

        $fieldset->addField('asecsub_name', 'text', [
            'label'    => __('Name'),
            'required' => true,
            'name'     => 'asecsub_name',
            'value'    => $articlesubsections->getAsecsubName(false),
            'after_element_js' => '
                <script>
                    require(["Magento_Ui/js/form/element/wysiwyg"], function () {});
                </script>
            ',
        ]);

        $fieldset->addField('asecsub_value', 'editor', [
            'label'    => __('Text'),
            'required' => false,
            'name'     => 'asecsub_value',
            'value'    => $articlesubsections->getAsecsubValue(),
            'wysiwyg'  => true,
            'config'   => $this->wysiwygConfig->getConfig(),
            'style'    => 'height:35em',
        ]);

        $fieldset->addField('asecsub_is_active', 'select', [
            'label'  => __('Is Active'),
            'name'   => 'asecsub_is_active',
            'value'  => $articlesubsections->getId() ? $articlesubsections->getAsecsubIsActive() : true,
            'values' => [0 => __('No'), 1 => __('Yes')],

        ]);
        $fieldset->addField('asecsub_position', 'text', [
            'label' => __('Sort Order'),
            'name'  => 'asecsub_position',
            'value' => $articlesubsections->getAsecsubPosition(),

        ]);

        /*
        $this->addStoreField($fieldset, $article);

        $groups = $this->getGroupCollectionFactory()->create()->toOptionArray();
        array_unshift($groups, ['value' => Articlesubsections::ALL_GROUPS_KEY, 'label' => __('All Groups')->getText()]);
        $fieldset->addField('customer_group_ids', 'multiselect', [
            'label'    => __('Customer Groups'),
            'required' => true,
            'name'     => 'customer_group_ids[]',
            'value'    => $articlesubsections->getCustomerGroupIds(),
            'values'   => $groups,
        ]);

        $fieldset->addField('user_id', 'select', [
            'label'  => __('Author'),
            'name'   => 'user_id',
            'value'  => $articlesubsections->getUserId(),
            'values' => $this->kbData->toAdminUserOptionArray(),

        ]);


        $tags = [];
        foreach ($articlesubsections->getTags() as $tag) {
            $tags[] = $tag->getName();
        }

        $fieldset->addField('tags', 'text', [
            'label' => __('Tags'),
            'name'  => 'tags',
            'value' => implode(', ', $tags),
        ]);

                $container = 'kb_article_categories';
                $updateUrl = $this->getUrl('mui/index/render');
                $renderUrl = $this->getUrl('mui/index/render_handle', [
                        'handle'  => 'kb_category_create',
                        'buttons' => 1
                    ]
                );

                $fieldset->addField('categories', 'hidden', [
                    'name'             => 'categories',
                    'value'            => implode(',', (array)$articlesubsections->getData('category_ids')),
                    'after_element_js' => $this->formCategoryHelper->getCategoryField(
                        $articlesubsections, $container, $updateUrl, $renderUrl
                    ),
                ]);
                */

        return $this;
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param \Mirasvit\Kb\Model\Articlesubsections                    $articlesubsections
     *
     * @return void
     */
    protected function addStoreField($fieldset, $articlesubsections)
    {
        if ($this->context->getStoreManager()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'hidden', [
                'name'  => 'store_ids[]',
                'value' => $this->context->getStoreManager()->getStore(true)->getId(),
            ]);
        } else {
            $container = 'kb_article_store_views';
            $fieldset->addField('store_ids', 'hidden', [
                'name'             => 'store_ids',
                'value'            => implode(',', $articlesubsections->getStoreIds()),
                'after_element_js' => $this->articleStoreviewFormHelper->getField(
                    $articlesubsections, $container
                ),
            ]);
        }
    }

    /**
     * @return \Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface
     */
    private function getArticlesubsectionsMagagement()
    {
        return $this->objectManager->get('\Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface');
    }

}
