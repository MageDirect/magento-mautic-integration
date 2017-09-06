<?php

class MageDirect_MauticIntegration_Adminhtml_MauticController extends Mage_Adminhtml_Controller_Action
{

    public function authorizeAction()
    {
        $result = Mage::getSingleton('mdmautic/mautic')->authorize();
        
        $block = $this->getLayout()->createBlock('core/template', 'mautic_auth_result');
        if ($block) {
            $block->setTemplate('mdmautic/result.phtml');
            if ($result===true || isset($result['access_token'])) {
                $block->setResult('success');
            }

            if (isset($result['errors'])) {
                $block->setResult('error')
                    ->setAuthErrors($result['errors']);
            }

            echo $block->toHtml();
            return;
        }

        echo $this->_helper()->__("Something went wrong!");
        return;
    }

    /**
     * Retrieve helper
     *
     * @return MageDirect_MauticIntegration_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mdmautic');
    }
}