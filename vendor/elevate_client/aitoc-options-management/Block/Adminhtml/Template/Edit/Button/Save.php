<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Block\Adminhtml\Template\Edit\Button;

use Magento\Ui\Component\Control\Container;

/**
 * Class Save
 */
class Save extends Generic
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'option_template_edit.option_template_edit',
                                'actionName' => 'save',
                                'params' => [false]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions()
        ];
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        if (!$this->context->getRequestParam('popup')) {
            $options[] = [
                'label' => __('Save & Duplicate'),
                'id_hard' => 'save_and_duplicate',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'option_template_edit.option_template_edit',
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        ['back' => 'duplicate']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ];
        }

        $options[] = [
            'id_hard' => 'save_and_close',
            'label' => __('Save & Close'),
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'option_template_edit.option_template_edit',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    ['back' => 'grid']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];

        return $options;
    }
}
