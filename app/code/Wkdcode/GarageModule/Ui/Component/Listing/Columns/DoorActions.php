<?php
namespace Wkdcode\GarageModule\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Trace\Trace;

class DoorActions extends Column
{
    /**
     * Delete Url path
     */
    const ROW_DELETE_URL = 'wkdcode/door/delete';


    /**
     *  Category edit url path
     */
    const ROW_EDIT_URL = 'wkdcode/door/edit';

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var string
     */
    private $_editUrl;

    /**
     * Action constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL
    )
    {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as $index => &$item) {
                $field = $item['id_field_name'];
                $name = $this->getData('name');
                if (isset($item[$field])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            self::ROW_EDIT_URL,
                            ['id' => $item[$field]]
                        ),
                        'label' => __('Edit'),
                    ];

                    // $item[$name]['remove'] = [
                    //     'href' => $this->_urlBuilder->getUrl(self::ROW_DELETE_URL, ['id' => $item['value_id']]),
                    //     'label' => __('Remove'),
                    //     'confirm' => [
                    //         'title' => __('Remove Featured Category'),
                    //         'message' => __('Do you want to remove the Featured Category option on "${ $.$data.category_name }"?')
                    //     ]
                    // ];
                }
            }
        }

        return $dataSource;
    }
}
