<?php

class MageDirect_MauticIntegration_Adminhtml_ContactController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Export contacts to mautic
     */
    public function exportAction()
    {
        $result = array(
            'success' => true
        );
        try {
            $result['success'] = Mage::getSingleton('mdmautic/mautic_contact')->export();
        } catch(Exception $e) {
            $result = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }

        $jsonData = Mage::helper('core')->jsonEncode($result);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
