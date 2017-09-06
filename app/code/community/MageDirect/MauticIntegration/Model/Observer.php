<?php

class MageDirect_MauticIntegration_Model_Observer
{
    /**
     * Export customer
     *
     * @param Varien_Event_Observer $observer
     */
    public function exportCustomer(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getDataObject();
        $helper = Mage::helper('mdmautic');
        if ($customer->getId() && $helper->isEnabled() && $helper->isCustomerIntegrationEnabled()) {
            Mage::getSingleton('mdmautic/mautic_contact')->exportCustomer($customer);
        }
    }
}
