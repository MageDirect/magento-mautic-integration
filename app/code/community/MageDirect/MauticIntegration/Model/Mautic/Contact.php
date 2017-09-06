<?php


class MageDirect_MauticIntegration_Model_Mautic_Contact extends Mage_Core_Model_Abstract
{
    /**
     * Mautic address 1 field
     */
    const MAUTIC_CUSTOMER_ADRESS1 = 'address1';

    /**
     * Mautic address 2 field
     */
    const MAUTIC_CUSTOMER_ADRESS2 = 'address2';

    /**
     * Mautic postcode field
     */
    const MAUTIC_CUSTOMER_ZIPCODE = 'zipcode';

    /**
     * Mautic country field
     */
    const MAUTIC_CUSTOMER_COUNTRY = 'country';

    /**
     * Mautic region field
     */
    const MAUTIC_CUSTOMER_STATE = 'state';

    /**
     * Mautic city field
     */
    const MAUTIC_CUSTOMER_CITY = 'city';

    /**
     * Mautic company field
     */
    const MAUTIC_CUSTOMER_COMPANY = 'company';

    /**
     * Mautic phone field
     */
    const MAUTIC_CUSTOMER_PHONE = 'phone';

    /**
     * @var \Mautic\Api\Api
     */
    protected $_contactApi;

    /**
     * Export contacts
     */
    public function export()
    {
        $customers = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('*');

        foreach ($customers as $customer) {
            $this->exportCustomer($customer);
        }

        return true;
    }

    /**
     * Export customer
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return bool
     */
    public function exportCustomer($customer)
    {
        $data = $customer->getData();
        $address = $this->_getCustomerAddress($customer);

        if ($address) {
            $data = array_merge($data, $address);
        }

        $response = $this->_getContactApi()->create($data);

        if (isset($response['errors']) && count($response['errors'])) {
            Mage::getSingleton('mdmautic/mautic')
                ->executeErrorResponse($response);

            return false;
        }

        return true;
    }

    /**
     * Retrieve customer address
     *
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _getCustomerAddress($customer)
    {
        $address = false;
        if ($customer->getPrimaryBillingAddress()) {
            $address = $customer->getPrimaryBillingAddress();
        } elseif ($customer->getPrimaryShippingAddress()) {
            $address = $customer->getPrimaryShippingAddress();
        } elseif ($customer->getAddresses()) {
            $addresses = $customer->getAddresses();
            $address = array_shift($addresses);
        }

        if ($address) {

            $country = Mage::getModel('directory/country')->loadByCode($address->getCountry());

            return array(
                self::MAUTIC_CUSTOMER_ADRESS1 => $address->getStreet1(),
                self::MAUTIC_CUSTOMER_ADRESS2 => $address->getStreet2(),
                self::MAUTIC_CUSTOMER_ZIPCODE => $address->getPostcode(),
                self::MAUTIC_CUSTOMER_COUNTRY => $country->getName(),
                self::MAUTIC_CUSTOMER_STATE => $address->getRegion(),
                self::MAUTIC_CUSTOMER_CITY => $address->getCity(),
                self::MAUTIC_CUSTOMER_COMPANY => $address->getCompany(),
                self::MAUTIC_CUSTOMER_PHONE => $address->getTelephone()
            );
        }

        return false;
    }

    /**
     * Retrieve contact api
     *
     * @return \Mautic\Api\Api
     */
    protected function _getContactApi()
    {
        if ($this->_contactApi == null) {
            $mautic = Mage::getSingleton('mdmautic/mautic');
            $this->_contactApi = $mautic->getApi('contacts');
        }
        return $this->_contactApi;
    }
}
