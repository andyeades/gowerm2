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

use Mirasvit\Kb\Model\Articlesections;

class General extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var \Mirasvit\Kb\Helper\Form\Articlesections\Storeview
     */
    private $articleStoreviewFormHelper;
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    private $context;
    /**
     * @var \Mirasvit\Kb\Helper\Form\Articlesections\Category
     */
    private $formCategoryHelper;
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
     * @var \Mirasvit\Kb\Helper\Form\Articlesections
     */
    private $articlesectionsFormHelper;
    /**
     * @var \Mirasvit\Kb\Api\Service\Articlesections\ArticlesectionsManagementInterface
     */
    private $articlesectionsManagement;
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $backendUrl;

    /**
     * @var \Mirasvit\Kb\Api\ArticleRepositoryInterface
     */
    protected $articleRepository;


    /**
     * General constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Mirasvit\Kb\Helper\Form\Article\Category $formCategoryHelper
     * @param \Mirasvit\Kb\Api\ArticleRepositoryInterface $articleRepository
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
        \Mirasvit\Kb\Api\ArticleRepositoryInterface $articleRepository,
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
        $this->articleRepository      = $articleRepository;
        $this->backendUrl             = $backendUrl;
        $this->kbData                 = $kbData;
        $this->formFactory            = $formFactory;
        $this->registry               = $registry;
        $this->context                = $context;
        $this->wysiwygConfig          = $wysiwygConfig;

        $this->articlesectionsManagement          = $this->getArticlesectionsMagagement();
        $this->articlesectionsFormHelper          = $this->getArticlesectionsFormHelper();
        $this->articlesectionsStoreviewFormHelper = $this->getArticlesectionsStoreviewFormHelper();

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
        /** @var \Mirasvit\Kb\Model\Articlesections $articlesection*/
        $articlesection = $this->registry->registry('current_articlesection');

        $fieldset = $form->addFieldset('edit_fieldset', [
            'class'  => 'fieldset-wide field-article-form',
            'legend' => __('General Information'),
        ]);
        if ($articlesection->getId()) {
            $fieldset->addField('articlesection_id', 'hidden', [
                'name'  => 'articlesection_id',
                'value' => $articlesection->getId(),
            ]);
        }
        $fieldset->addField('parentarticle_id', 'select', [
            'label'    => __('Parent Article'),
            'required' => true,
            'name'     => 'parentarticle_id',
            'value'    => $articlesection->getParentarticleId(),
            'values'   => $this->kbData->toArticleOptionArray(),
        ]);

        $fieldset->addField('asec_name', 'text', [
            'label'    => __('Name'),
            'required' => true,
            'name'     => 'asec_name',
            'value'    => $articlesection->getAsecName(false),
            'after_element_js' => '
                <script>
                    require(["Magento_Ui/js/form/element/wysiwyg"], function () {});
                </script>
            ',
        ]);

        $fieldset->addField('asec_value', 'editor', [
            'label'    => __('Text'),
            'required' => false,
            'name'     => 'asec_value',
            'value'    => $articlesection->getAsecValue(),
            'wysiwyg'  => true,
            'config'   => $this->wysiwygConfig->getConfig(),
            'style'    => 'height:35em',
        ]);

        $fieldset->addField('asec_is_active', 'select', [
            'label'  => __('Is Active'),
            'name'   => 'asec_is_active',
            'value'  => $articlesection->getId() ? $articlesection->getAsecIsActive() : true,
            'values' => [0 => __('No'), 1 => __('Yes')],

        ]);
        $fieldset->addField('asec_position', 'text', [
            'label' => __('Sort Order'),
            'name'  => 'asec_position',
            'value' => $articlesection->getAsecPosition(),

        ]);

        /*
        $this->addStoreField($fieldset, $article);

        $groups = $this->getGroupCollectionFactory()->create()->toOptionArray();
        array_unshift($groups, ['value' => Articlesections::ALL_GROUPS_KEY, 'label' => __('All Groups')->getText()]);
        $fieldset->addField('customer_group_ids', 'multiselect', [
            'label'    => __('Customer Groups'),
            'required' => true,
            'name'     => 'customer_group_ids[]',
            'value'    => $articlesection->getCustomerGroupIds(),
            'values'   => $groups,
        ]);

        $fieldset->addField('user_id', 'select', [
            'label'  => __('Author'),
            'name'   => 'user_id',
            'value'  => $articlesection->getUserId(),
            'values' => $this->kbData->toAdminUserOptionArray(),

        ]);


        $tags = [];
        foreach ($articlesection->getTags() as $tag) {
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
                    'value'            => implode(',', (array)$articlesection->getData('category_ids')),
                    'after_element_js' => $this->formCategoryHelper->getCategoryField(
                        $articlesection, $container, $updateUrl, $renderUrl
                    ),
                ]);
                */

        return $this;
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param \Mirasvit\Kb\Model\Articlesections                    $articlesection
     *
     * @return void
     */
    protected function addStoreField($fieldset, $articlesection)
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
                'value'            => implode(',', $articlesection->getStoreIds()),
                'after_element_js' => $this->articleStoreviewFormHelper->getField(
                    $articlesection, $container
                ),
            ]);
        }
    }

    /**
     * @return \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface
     */
    private function getArticleMagagement()
    {
        return $this->objectManager->get('\Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface');
    }

    /**
     * @return \Mirasvit\Kb\Helper\Form\Article
     */
    private function getArticleFormHelper()
    {
        return $this->objectManager->get('\Mirasvit\Kb\Helper\Form\Article');
    }

    /**
     * @return \Mirasvit\Kb\Helper\Form\Article\Storeview
     */
    private function getArticleStoreviewFormHelper()
    {
        return $this->objectManager->get('\Mirasvit\Kb\Helper\Form\Article\Storeview');
    }

    /**
     * @return \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    private function getGroupCollectionFactory()
    {
        return $this->objectManager->get('\Magento\Customer\Model\ResourceModel\Group\CollectionFactory');
    }
}
