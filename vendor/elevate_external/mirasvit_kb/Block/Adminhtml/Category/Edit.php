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



namespace Mirasvit\Kb\Block\Adminhtml\Category;

/**
 * Category container block.
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var string
     */
    protected $_template = 'category/edit.phtml';//@codingStandardsIgnoreLine

    /**
     * Edit constructor.
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->productMetadata = $productMetadata;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_objectId = 'category_id';
        $this->_blockGroup = 'Mirasvit_Kb';
        $this->_controller = 'adminhtml_category';
        $this->_mode = 'edit';
        parent::_construct();
        $this->buttonList->remove('back');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('save');
    }

    /**
     * @return string
     */
    public function getWysiwygName()
    {
        $name = 'wysiwygAdapter';
        if (version_compare($this->productMetadata->getVersion(), '2.3.0', '<')) {
            $name = 'tinymce';
        }

        return $name;
    }
}
