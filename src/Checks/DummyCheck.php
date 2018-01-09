<?php
namespace CodeOrange\Statuspage\Checks;

use CodeOrange\Statuspage\Status;

/**
 * A DummyCheck just returns the status it was given
 *
 * @package CodeOrange\Statuspage\Checks
 */
class DummyCheck extends StatusCheck {
	/** @var $status Status */
	private $status;

	/**
	 * DummyCheck constructor.
	 * @param Status $status The status you want this check to return
	 */
	public function __construct(Status $status) {
		$this->status = $status;
	}

	/**
	 * @return Status The status that was given in the constructor
	 */
	public function performCheck() {
		return $this->status;
	}
}