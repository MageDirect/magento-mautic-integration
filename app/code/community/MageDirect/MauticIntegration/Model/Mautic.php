<?php
require_once(Mage::getBaseDir('lib') . '/mautic-autoloader.php');

class MageDirect_MauticIntegration_Model_Mautic
{
    /**
     * @var \Mautic\Auth\AuthInterface
     */
    protected $_auth;

    /**
     * Retrieve auth
     *
     * @return \Mautic\Auth\AuthInterface
     */
    public function getAuth()
    {
        if ($this->_auth == null) {
            $settings = $this->_getSettings();
            $helper = $this->_helper();

            $initAuth = new Mautic\Auth\ApiAuth();
            if ($helper->getAuthType() == MageDirect_MauticIntegration_Model_Config_Source_OauthVersion::AUTH_BASIC) {
                $this->_auth = $initAuth->newAuth(
                    $settings,
                    MageDirect_MauticIntegration_Model_Config_Source_OauthVersion::AUTH_BASIC
                );
            } else {
                $this->_auth = $initAuth->newAuth($settings);
            }
        }

        return $this->_auth;
    }

    /**
     * Retrieve api by type
     * @see https://developer.mautic.org/
     *
     * @param $type
     * @return Mautic\Api\Api
     */
    public function getApi($type)
    {
        $api = new Mautic\MauticApi();
        return $api->newApi($type, $this->getAuth(), $this->_helper()->getMauticUrl());
    }

    /**
     * Execute error
     *
     * @param array $response
     */
    public function executeErrorResponse($response)
    {
        Mage::log($response); //temporary. Better solution required
    }

    /**
     *  Initiate process for obtaining an access token; this will
     *  redirect the user to the authorize endpoint and/or set the tokens
     *  when the user is redirected back after granting authorization
     *
     * @return bool
     */
    public function authorize()
    {
        $auth = $this->getAuth();

        try {
            if ($auth->validateAccessToken()) {
                // Obtain the access token returned; call accessTokenUpdated() to catch if the token was updated via a
                // refresh token

                // $accessTokenData will have the following keys:
                // For OAuth1.0a: access_token, access_token_secret, expires
                // For OAuth2: access_token, expires, token_type, refresh_token
                if ($auth->accessTokenUpdated()) {
                    $accessTokenData = $auth->getAccessTokenData();
                    //store access token data in default scope config
                    $this->_helper()->updateStoredAccessTokenData($accessTokenData);
                    return $accessTokenData;
                }
                return true;
            }
        } catch (Exception $e) {
            // Do Error handling
            $error = array(
                'message'=> $e->getMessage()
            );
            return array('errors' => array($error));
        }

        return false;
    }


    /**
     * Retrieve auth setting
     * @return array;
     */
    protected function _getSettings()
    {
        $helper = $this->_helper();

        //Mautic can be configured to basic auth. In this case need only login and password to Mautic
        if ($helper->getAuthType() == MageDirect_MauticIntegration_Model_Config_Source_OauthVersion::AUTH_BASIC) {
            return array(
                'userName'      => $helper->getLogin(),
                'password'      => $helper->getPassword(),
            );
        }

        $settings = array(
            'baseUrl'      => $helper->getMauticUrl(),
            'version'      => $helper->getAuthType(),
            'clientKey'    => $helper->getClientKey(),
            'clientSecret' => $helper->getClientSecret(),
            'callback'     => $helper->getCallbackUrl()
        );

        // If you already have the access token, et al, pass them in as well to prevent the need for reauthorization
        $accessTokenData = $helper->getStoredAccessTokenData();
        $settings['accessToken']        = isset($accessTokenData['access_token']) ? $accessTokenData['access_token'] : null;
        $settings['accessTokenSecret']  = isset($accessTokenData['access_token_secret']) ? $accessTokenData['access_token_secret'] : null; //for OAuth1.0a
        $settings['accessTokenExpires'] = isset($accessTokenData['expires']) ? $accessTokenData['expires'] : null; //UNIX timestamp
        $settings['refreshToken']       = isset($accessTokenData['refresh_token']) ? $accessTokenData['refresh_token'] : null;

        return $settings;
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
