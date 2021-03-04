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

use Magento\Framework\Data\OptionSourceInterface;

class ArticleOptions implements OptionSourceInterface
{

    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Article\Collection
     */
    private $articleCollection;

    /**
     * ArticleOptions constructor.
     * @param \Mirasvit\Kb\Model\ResourceModel\Article\Collection $articleCollection
     */
    public function __construct(\Mirasvit\Kb\Model\ResourceModel\Article\Collection $articleCollection)
    {
        $this->articleCollection = $articleCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $content = $this->articleCollection->toOptionArray();

        array_push($content, ['value' => 0, 'label' => __('All Articles')->getText()]);

        return $content;
    }
}
