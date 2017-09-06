<?php

class MageDirect_MauticIntegration_Model_Config_Source_OauthVersion
{

    const AUTH_OAUTH1 = 'OAuth1a';
    const AUTH_OAUTH2 = 'OAuth2';
    const AUTH_BASIC = 'BasicAuth';

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value'=> self::AUTH_OAUTH1, 'label'=>Mage::helper('mdmautic')->__('OAuth 1')),
            array('value'=> self::AUTH_OAUTH2, 'label'=>Mage::helper('mdmautic')->__('OAuth 2')),
            array('value'=> self::AUTH_BASIC, 'label'=>Mage::helper('mdmautic')->__('Basic Auth')),
        );
    }
}
