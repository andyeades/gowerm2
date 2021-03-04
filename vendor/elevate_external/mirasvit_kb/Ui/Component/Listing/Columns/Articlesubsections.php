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


namespace Mirasvit\Kb\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

use Mirasvit\Kb\Model\ArticlesubsectionsFactory;

/**
 * Class Articlesubsections
 */
class Articlesubsections extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var mixed
     */
    private $articlesubsectionsCategoryHelper;
    /**
     * @var string
     */
    private $storeKey;
    /**
     * @var ArticlesubsectionsFactory
     */
    private $articlesubsectionsFactory;

    /**
     * @param ArticlesubsectionsFactory $articlesubsectionsFactory
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param string $storeKey
     */
    public function __construct(
        ArticlesubsectionsFactory $articlesubsectionsFactory,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
        $storeKey = 'articlesubsection_id'
    ) {
        $this->articlesubsectionsFactory = $articlesubsectionsFactory;
        $this->storeKey       = $storeKey;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('asecsub_name');
            foreach ($dataSource['data']['items'] as &$item) {
                $articlesubsections = $this->articleFactory->create();
                $articlesubsections->getResource()->load($articlesubsections, $item[$fieldName]);
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $articlesubsections->getId() ? $articlesubsections->getAsecName() : __('Articlesubsections does not exist');
                }
            }
        }

        return $dataSource;
    }

    /**
     * Modilied to display All Stores for 0
     * {@inheritdoc}
     */
    protected function prepareItem(array $item)
    {
        $content = '';
        if (!empty($item[$this->storeKey])) {
            $origCategories = explode(',', $item[$this->storeKey]);
        }

        if (empty($origCategories)) {
            return '';
        }
        if (!is_array($origCategories)) {
            $origCategories = [$origCategories];
        }
//@fixme articleCategoryHelper is not defined
        $categoryTree = $this->articlesubsectionsCategoryHelper->getCategoriesTree();

        $content = '';
        $content = $this->buildOptions($content, $categoryTree, $origCategories);

        return $content;
    }

    /**
     * @param array $content
     * @param array $categoryTree
     * @param array $origCategories
     * @return array
     */
    protected function buildOptions($content, $categoryTree, $origCategories)
    {
        foreach ($categoryTree as $category) {
            $prefix = str_repeat('&nbsp;', ($category['level'] - 1) * 3);
            $label = $category['label'];
            if (in_array($category['value'], $origCategories)) {
                $label = '<b>' . $label . '</b>';
            }
            $content .= $prefix . $label .'<br>';
            if (isset($category['optgroup'])) {
                $content = $this->buildOptions($content, $category['optgroup'], $origCategories);
            }
        }

        return $content;
    }
}
