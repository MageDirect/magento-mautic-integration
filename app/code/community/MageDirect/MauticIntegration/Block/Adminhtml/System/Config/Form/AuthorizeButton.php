<?php

class MageDirect_MauticIntegration_Block_Adminhtml_System_Config_Form_AuthorizeButton extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     * Unset some non-related element parameters
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element) {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->addData(array(
            'html_id' => $element->getHtmlId(),
        ));
        return $this->getButtonHtml();
    }
    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAuthorizeUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/mautic/authorize');
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
                'id'        => $this->getHtmlId(),
                'label'     => $this->helper('mdmautic')->__('Authorize API'),
                'onclick'   => "window.open('" . $this->getAuthorizeUrl() . "', 'mautic_authorize', 'width=400,height=400')"
            ));

        return $button->toHtml();
    }
}
