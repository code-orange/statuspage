<?php
namespace CodeOrange\Statuspage\Checks;

use Carbon\Carbon;
use CodeOrange\Statuspage\Status;

/**
 * Class InTimeCheck
 *
 * Checks if a given time is within acceptable range
 *
 * @package CodeOrange\Statuspage\Checks
 */
class InTimeCheck extends StatusCheck {
	/** @var $time Carbon */
	private $time;
	/** @var $acceptable Carbon */
	private $acceptable;
	/** @var $critical Carbon */
	private $critical;

	/**
	 * InTimeCheck constructor.
	 * @param Carbon $time
	 * @param $acceptable string Time that is still acceptable
	 * @param null $critical string Time that is not acceptable. If given, if the time falls between $acceptable and $critical, this check will return a partial outage
	 */
	public function __construct(Carbon $time, $acceptable, $critical = null) {
		$this->time = $time;
		$this->acceptable = new Carbon($acceptable);
		if ($critical) {
			$this->critical = new Carbon($critical);
		}
	}


	/**
	 * @return Status
	 */
	public function performCheck() {
		if ($this->time->lessThanOrEqualTo($this->acceptable)) {
			if ($this->critical && $this->time->greaterThan($this->critical)) {
				return Status::$PARTIAL_OUTAGE->withExplanation('Reported time is ' . $this->acceptable->diffForHumans($this->time, true) . ' too late');
			} else {
				return Status::$MAJOR_OUTAGE->withExplanation('Reported time is ' . $this->acceptable->diffForHumans($this->time, true) . ' too late');
			}
		} else {
			return Status::$OPERATIONAL->withExplanation('Reported time was ' . $this->time->diffForHumans());
		}
	}
}