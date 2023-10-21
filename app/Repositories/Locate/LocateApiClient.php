<?php

namespace App\Repositories\Locate;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\Psr7\Request;

class LocateApiClient
{
	/**
	 * Flow route api base url 
	 * @var string
	 */
	public static $baseUrl = 'https://test_rebelsmuggling.locateinv.com';

	/**
	 * @var string
	 */
	protected $endPoint;

	/**
	 * @var string
	 */
	protected $sessionToken;

	/**
	 * @param string $accessKey
	 * @param string $secretKey
	 */
	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => static::$baseUrl,
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			]
		]);
	}

	/**
	 * Authentificate
	 * 
	 * @return mixed
	 */
	protected function getSessionToken()
	{
		$response = $this->client->request('POST', 'login', [
				'form_params' => [
			        'email' => config('services.locate.email'),
					'password' => config('services.locate.password'),
			    ]
			]);

		$response = json_decode((string) $response->getBody());

		return $response->session_token;
	}

	/**
	 * @return boolean
	 */
	public function isAuth()
	{
		return is_string($this->sessionToken);
	}

	/**
	 * @return string
	 */
	public function getEndpoint()
	{
		$endPoint = $this->endPoint;

		if (! Str::startsWith($endPoint, '/')) {
			$endPoint = '/' . $endPoint;
		}

		return $endPoint;
	}

	/**
	 * @param string $endPoint
	 * @return self
	 */
	public function setEndpoint(string $endPoint)
	{
		$this->endPoint = $endPoint;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return self::$baseUrl . $this->getEndpoint();
	}

	/**
	 * Make http request to flowroute API
	 * 
	 * @param  string $method 
	 * @param  array  $headers
	 * @return GuzzleHttp\Psr7\Response
	 */
	public function makeRequest(string $method = 'GET', array $headers = [])
	{
		if (trim($this->sessionToken) === '') {
			$this->sessionToken = $this->getSessionToken();
		}

		if (! is_string($this->sessionToken)) {
			throw new Exception("Invalid session token");
		}

		$headers['auth'] = [$this->sessionToken, ''];

		return $this->client->request(
			strtoupper($method), $this->getEndpoint(), $headers
		);
	}
}