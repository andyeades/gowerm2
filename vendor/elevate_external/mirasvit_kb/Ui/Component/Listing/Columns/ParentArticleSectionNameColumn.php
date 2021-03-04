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

class ParentArticleSectionNameColumn extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
        \Mirasvit\Kb\Api\ArticlesectionRepositoryInterface
     *
     */
    protected $articlesectionsRepository;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Mirasvit\Kb\Api\ArticlesectionsRepositoryInterface $articlesectionsRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Mirasvit\Kb\Api\ArticlesectionsRepositoryInterface $articlesectionsRepository,
        array $components = [],
        array $data = []
    ) {
        $this->articlesectionsRepository = $articlesectionsRepository;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $parent_article_section_id = $item['parentarticlesection_id'];
                $articlesection = $this->articlesectionsRepository->getById($parent_article_section_id);
                $articlesection_name = $articlesection->getAsecName();
                $item['parentarticlesection_id'] = $parent_article_section_id.' - '.$articlesection_name;
            }
        }

        return $dataSource;
    }
}
