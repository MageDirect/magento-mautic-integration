<?php

class MageDirect_MauticIntegration_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Path to module status
     */
    const MODULE_STATUS_XML_PATH = 'mdmautic/general/enable';

    /**
     * Path to mautic url configuration
     */
    const MAUTIC_URL_XML_PATH = 'mdmautic/general/mautic_url';

    /**
     * Path to client id configuration
     */
    const CLIENT_ID_XML_PATH = 'mdmautic/general/client_id';

    /**
     * Path to oauth version
     */
    const OAUTH_TYPE_XML_PATH = 'mdmautic/general/oauth_version';

    /**
     * Path to client secret configuration
     */
    const CLIENT_SECRET_URL_XML_PATH = 'mdmautic/general/client_secret';

    /**
     * Path to access token
     */
    const CLIENT_ACCESS_TOKEN_XML_PATH = 'mdmautic/general/access_token_data';

    /**
     * Path to basic auth login configuration
     */
    const BASE_AUTH_LOGIC = 'mdmautic/general/mautic_login';

    /**
     * Path to basic auth password configuration
     */
    const BASE_AUTH_PASSWORD = 'mdmautic/general/mautic_password';

    /**
     * Contact integration status path
     */
    const CONTACT_INTEGRATION_STATUS = 'mdmautic/contact/enable';

    /**
     * Check is module enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::MODULE_STATUS_XML_PATH);
    }

    /**
     * Retrieve mautic url
     *
     * @return string
     */
    public function getMauticUrl()
    {
        return Mage::getStoreConfig(self::MAUTIC_URL_XML_PATH);
    }

    /**
     * Retrieve Client key
     *
     * @return string
     */
    public function getClientKey()
    {
        return Mage::getStoreConfig(self::CLIENT_ID_XML_PATH);
    }

    /**
     * Retrieve client secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return Mage::getStoreConfig(self::CLIENT_SECRET_URL_XML_PATH);
    }

    /**
     * Retrieve Oauth version
     *
     * @return string
     */
    public function getAuthType()
    {
        return Mage::getStoreConfig(self::OAUTH_TYPE_XML_PATH);
    }

    /**
     * Retrieve mautic login
     *
     * @return string
     */
    public function getLogin()
    {
        return Mage::getStoreConfig(self::BASE_AUTH_LOGIC);
    }

    /**
     * Retrieve base auth password
     *
     * @return string
     */
    public function getPassword()
    {
        return Mage::helper('core')->decrypt(Mage::getStoreConfig(self::BASE_AUTH_PASSWORD));
    }

    /**
     * Retrieve status of customer integration
     *
     * @return bool
     */
    public function isCustomerIntegrationEnabled()
    {
        return Mage::getStoreConfigFlag(self::CONTACT_INTEGRATION_STATUS);
    }

    /**
     * Retrieve access token data
     *
     * @return bool|array
     */
    public function getStoredAccessTokenData()
    {
        $oauth_version = strtolower(Mage::getStoreConfig(self::OAUTH_TYPE_XML_PATH));
        $token = Mage::getStoreConfig(self::CLIENT_ACCESS_TOKEN_XML_PATH.'_'.$oauth_version);
        if ($token) {
            return Mage::helper('core')->jsonDecode($token);
        }

        return false;
    }

    /**
     * Save access token
     *
     * @param $accessTokenData array
     * @return bool
     */
    public function updateStoredAccessTokenData($accessTokenData = array())
    {
        $tokenJson = Mage::helper('core')->jsonEncode($accessTokenData);
        $oauth_version = strtolower(Mage::getStoreConfig(self::OAUTH_TYPE_XML_PATH));
        if (empty($accessTokenData)) {
            Mage::getConfig()->deleteConfig(self::CLIENT_ACCESS_TOKEN_XML_PATH.'_'.$oauth_version);
        }
        else {
            Mage::getConfig()->saveConfig(self::CLIENT_ACCESS_TOKEN_XML_PATH.'_'.$oauth_version, $tokenJson);
        }

        Mage::getConfig()->cleanCache();

        return $this;
    }

    /**
     * Retrieve callback url
     *
     * @return bool
     */
    public function getCallbackUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/mautic/authorize');
    }

}
