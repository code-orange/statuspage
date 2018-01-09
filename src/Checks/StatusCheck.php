<?php
namespace CodeOrange\Statuspage\Checks;

use CodeOrange\Statuspage\Status;

abstract class StatusCheck {
	/**
	 * @return Status
	 */
	public abstract function performCheck();
}
