<?php

namespace FME\GoogleMerchant;

class ContentSession {

    public $config;
    public $merchantId;
    public $mcaStatus;
    public $service;
    public $sandboxService;
    public $websiteUrl;

    protected $configDir;

    const OAUTH_CLIENT_FILE_NAME = 'client-secrets.json';
    const OAUTH_TOKEN_FILE_NAME = 'stored-token.json';

    // Constructor that sets up configuration and authentication for all
    // the samples.
    public function __construct () 
    {
        $this->configDir = config('google-api.config_path');

        $this->config = config('google-api.merchant-info');

        if (is_null($this->config)) {
            throw new \InvalidArgumentException('config file not found!');
        }

        $client = app()->make('Google_Client');
        $client->setApplicationName('Content API for Shopping Samples');
        $client->setScopes(\Google_Service_ShoppingContent::CONTENT);

        $this->authenticate($client);
        $this->prepareServices($client);
        $this->retrieveConfig();
    }

    /**
    * Prepares the service and sandboxService fields, taking into
    * consideration any needed endpoint changes.
    */
    private function prepareServices($client) {
        
        $this->service = new \Google_Service_ShoppingContent($client);

        // Fetch the standard rootUrl and basePath to set things up
        // for sandbox creation.
        $class = new \ReflectionClass('Google_Service_Resource');
        $rootProperty = $class->getProperty('rootUrl');
        $rootProperty->setAccessible(true);
        $pathProperty = $class->getProperty('servicePath');
        $pathProperty->setAccessible(true);
        $rootUrl = $rootProperty->getValue($this->service->accounts);
        $basePath = $pathProperty->getValue($this->service->accounts);

        // Attempt to determine a sandbox endpoint from the given endpoint.
        // If we can't, then fall back to using the same endpoint for
        // sandbox methods.
        $pathParts = explode('/', rtrim($basePath, '/'));
        
        if ($pathParts[count($pathParts) - 1] === 'v2') {
            $pathParts = array_slice($pathParts, 0, -1);
            $pathParts[] = 'v2sandbox';
            $basePath = implode('/', $pathParts) . '/';
        }

        $this->sandboxService = $this->getServiceWithEndpoint($client, $rootUrl, $basePath);
    }

    /**
    * Creates a new Content API service object from the given client
    * and changes the rootUrl and/or the basePath of the Content API
    * service resource objects within.
    */
    private function getServiceWithEndpoint($client, $rootUrl, $basePath) 
    {
        $service = new \Google_Service_ShoppingContent($client);

        // First get the fields that are directly defined in
        // Google_Service_ShoppingContent, as those are the fields that
        // contain the different service resource objects.
        $gsClass = new \ReflectionClass('Google_Service');
        $gsscClass = new \ReflectionClass('Google_Service_ShoppingContent');
        $gsProps = $gsClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        $gsscProps = array_diff(
            $gsscClass->getProperties(\ReflectionProperty::IS_PUBLIC), $gsProps
        );

        // Prepare the properties we (may) be modifying in these objects.
        $class = new \ReflectionClass('Google_Service_Resource');
        $rootProperty = $class->getProperty('rootUrl');
        $rootProperty->setAccessible(true);
        $pathProperty = $class->getProperty('servicePath');
        $pathProperty->setAccessible(true);

        foreach ($gsscProps as $prop) {
          $resource = $prop->getValue($service);
          $rootProperty->setValue($resource, $rootUrl);
          $pathProperty->setValue($resource, $basePath);
        }

        return $service;
    }

    /**
    * Retrieves information that can be determined via API calls, including
    * configuration fields that were not provided.
    *
    * <p>Retrieves the following fields if missing:
    * <ul>
    * <li>merchantId
    * </ul>
    *
    * <p>Retrieves the following fields, ignoring any existing configuration:
    * <ul>
    * <li>isMCA
    * <li>websiteUrl
    * </ul>
    */
    public function retrieveConfig() 
    {
        $response = $this->service->accounts->authinfo();

        if (is_null($response->getAccountIdentifiers())) {
            throw new \InvalidArgumentException(
                'Authenticated user has no access to any Merchant Center accounts');
        }

        // If there is no configured Merchant Center account ID, use the first one
        // that this user has access to.
        if (array_key_exists('merchantId', $this->config)) {
            $this->merchantId = strval($this->config['merchantId']);
        } else {

            $firstAccount = $response->getAccountIdentifiers()[0];

            if (!is_null($firstAccount->getMerchantId())) {
                $this->merchantId = $firstAccount->getMerchantId();
            } else {
                $this->merchantId = $firstAccount->getAggregatorId();
            }
        }

        // The current account can only be an aggregator if the authenticated
        // account has access to it (is a user) and it's listed in authinfo as
        // an aggregator.
        $this->mcaStatus = false;
        foreach ($response->getAccountIdentifiers() as $accountId) {
            if (!is_null($accountId->getAggregatorId()) && ($accountId->getAggregatorId() === $this->merchantId)) {
                $this->mcaStatus = true;
                break;
            }

            if (!is_null($accountId->getMerchantId()) && ($accountId->getMerchantId() === $this->merchantId)) {
                break;
            }
        }

        $account = $this->service->accounts->get(
              $this->merchantId, $this->merchantId
        );

        $this->websiteUrl = $account->getWebsiteUrl();
    }

    /**
    * This function is used as a gate for methods that can only be run
    * on multi-client accounts.
    *
    * @throws InvalidArgumentException if the config does not contain an MCA.
    */
    const MCA_MSG = '';
    public function mustBeMCA($msg = self::MCA_MSG) 
    {
        if ($this->mcaStatus === false) {
            throw new \InvalidArgumentException($msg);
        }
    }

    /**
    * This function is used as a gate for methods that cannot be run
    * on multi-client accounts.
    *
    * @throws InvalidArgumentException if the config contains an MCA.
    */
    const NON_MCA_MSG = '';
    public function mustNotBeMCA($msg = self::NON_MCA_MSG) 
    {
        if ($this->mcaStatus === true) {
            throw new \InvalidArgumentException($msg);
        }
    }

    /**
    * Attempts to find the home directory of the user running the PHP script.
    *
    * @return string The path to the home directory with any trailing directory
    *     separators removed
    * @throws UnexpectedValueException if a home directory could not be found
    */
    public function getHome() 
    {
        $home = null;

        if (!empty(getenv('HOME'))) {
            // Try the environmental variables.
            $home = getenv('HOME');
        } else if (!empty($_SERVER['HOME'])) {
            // If not in the environment variables, check the superglobal $_SERVER as
            // a last resort.
            $home = $_SERVER['HOME'];
        } else if(!empty(getenv('HOMEDRIVE')) && !empty(getenv('HOMEPATH'))) {
            // If the 'HOME' environmental variable wasn't found, we may be on
            // Windows.
            $home = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        } else if(!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
            $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
        }

        if ($home === null) {
            throw new \UnexpectedValueException('Could not locate home directory.');
        }

        return rtrim($home, '\\/');
    }

    private function getToken(\Google_Client $client) 
    {
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->setScopes('https://www.googleapis.com/auth/content');
        $client->setAccessType('offline'); // So that we get a refresh token
        $code = trim(fgets(STDIN));
        $client->authenticate($code);
        return $client->getAccessToken();
    }

    protected function cacheToken(\Google_Client $client) 
    {
        $token = $this->getToken($client);
        $tokenFile = join(DIRECTORY_SEPARATOR, [$this->configDir, self::OAUTH_TOKEN_FILE_NAME]);
        file_put_contents($tokenFile, json_encode($token, JSON_PRETTY_PRINT));
    }

    /**
    * This function looks for authentication in this order:
    * - Google Application Default Credentials
    * - Service account credentials in SERVICE_ACCOUNT_FILE_NAME in the configDir
    * - OAuth2 credentials in OAUTH_CLIENT_FILE_NAME in the configDir
    */
    protected function authenticate(\Google_Client $client) 
    {
        if (!$this->configDir) {
            throw new \InvalidArgumentException(
                'Must use Google Application Default Credentials if running '
                . 'without a configuration directory'
            );
        }

        $this->authenticateFromConfig($client);
    }

    // Handles loading authentication credentials from the config dir.
    protected function authenticateFromConfig(\Google_Client $client) 
    {
        $accountFile = config('google-api.service-account');

        if (file_exists($accountFile)) {
            $client->setAuthConfig($accountFile);
            $client->setScopes(\Google_Service_ShoppingContent::CONTENT);
            return true;
        }
    
        // All authentication failed.
        $msg = sprintf('Could not find or read credentials from '
            . 'either the Google Application Default credentials, '
            . '%s, or %s.', $accountFile, $oauthFile);

        throw new \DomainException($msg);
    }

    // Retry a function with back off
    public function retry($object, $function, $parameter, $maxAttempts = 5) 
    {
        $attempts = 1;

        while ($attempts <= $maxAttempts) {
            try {
                return call_user_func([$object, $function], $parameter);
            } catch (\Google_Service_Exception $exception) {
                $sleepTime = $attempts * $attempts;
                sleep($sleepTime);
                $attempts++;
            }
        }
    }
}
