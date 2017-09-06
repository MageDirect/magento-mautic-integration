<?php

class MageDirect_MauticIntegration_Block_Adminhtml_System_Config_Form_Disabled extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * {@inheritdoc}
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $element->setDisabled(true);
        $element->setValue($this->get);
        return parent::_getElementHtml($element);
    }

    protected function _getCallbackUrl()
    {

    }
}
