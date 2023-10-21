<?php

namespace FME\GoogleMerchant\Contract;

use FME\GoogleMerchant\ContentSession;

abstract class GoogleMerchantContract {

  	protected $session;

	public function __construct($session = null) {
		$this->session = ($session === null) ? app()->make('ContentSession') : $session;
	}

	public function getSession()
	{
		return $this->session;
	}
}
