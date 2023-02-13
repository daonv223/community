<?php
declare(strict_types=1);

namespace DaoNguyen\Community\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Rank extends AbstractFieldArray
{
    protected function _prepareToRender()
    {
        $this->addColumn(
            'label',
            [
                'label' => __('Rank Label'),
                'class' => 'required-entry admin__control-text'
            ]
        );
        $this->addColumn(
            'point',
            [
                'label' => __('Point'),
                'class' => 'required-entry validate-number validate-greater-than-zero admin__control-text'
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Rank');
    }
}
