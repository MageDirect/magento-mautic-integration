<?php

class MageDirect_MauticIntegration_Block_Adminhtml_System_Config_Form_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Button template
     * @var string
     */
    protected $_template = 'mdmautic/system/config/button.phtml';

    /**
     * {@inheritdoc}
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxCheckUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/contact/export');
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'id'        => 'mdmautic_button',
                'label'     => $this->helper('adminhtml')->__('Export'),
                'onclick'   => 'javascript:exportCustomers(); return false;'
            ));

        return $button->toHtml();
    }
}
