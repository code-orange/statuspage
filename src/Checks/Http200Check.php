<?php
namespace CodeOrange\Statuspage\Checks;

use CodeOrange\Statuspage\Status;

class Http200Check extends StatusCheck {
	private $url;
	private $status = 200;

	/**
	 * Http200Check constructor.
	 * @param $url string The URL to check
	 * @param int $status Optionally, give a status other than 200 to check for
	 */
	public function __construct($url, $status = 200) {
		$this->url = $url;
		$this->status = $status;
	}

	/**
	 * @return Status
	 */
	public function performCheck() {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $this->url);
		curl_setopt($ch,CURLOPT_NOBODY,true);
		curl_exec($ch);
		$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);

		return ($code === $this->status) ? Status::$OPERATIONAL : Status::$MAJOR_OUTAGE;
	}
}
