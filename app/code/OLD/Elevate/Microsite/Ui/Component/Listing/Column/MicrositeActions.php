<?php

namespace Elevate\Microsite\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class MicrositeActions extends Column
{
    /** Url path */

    /** Url path */
    const URL_PATH_EDIT = 'elevate_microsite/microsite/edit';
    const URL_PATH_DELETE = 'elevate_microsite/microsite/delete';
    const URL_PATH_VIEW = 'elevate_microsite/microsite/view';

    protected $actionUrlBuilder;
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['microsite_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_EDIT, [
                                                   'microsite_id' => $item['microsite_id']
                                               ]
                        ),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_DELETE, [
                                                     'microsite_id' => $item['microsite_id']
                                                 ]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.title }"'),
                            'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                        ]
                    ];
                    $item[$name]['preview'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_VIEW, [
                                                   'microsite_id' => $item['microsite_id']
                                               ]
                        ),
                        'label' => __('View')
                    ];
                }
            }
        }

        return $dataSource;
    }
}