<?php
namespace CodeOrange\Statuspage;

use CodeOrange\Statuspage\Checks\StatusCheck;

class Statuspage {
	private $checks = [];

	/**
	 * Register a single new check to show up on the status page
	 *
	 * @param $label string Label for the check
	 * @param StatusCheck $sc
	 */
	public function registerCheck($label, StatusCheck $sc) {
		$this->checks[$label] = $sc;
	}

	/**
	 * Register a section of checks to show up on the status page
	 *
	 * @param $label string Label for the section
	 * @param $checks array Associative of the checks to add, with the key as label, ['Website reachable' => new Http200Check('http://example.com')]
	 */
	public function registerSection($label, $checks) {
		$this->checks[$label] = $checks;
	}

	/**
	 * Execute all registered checks and return the results
	 */
	public function execute() {
		return collect($this->checks)->map(function ($check, $label) {
			if ($check instanceof StatusCheck) {
				return $check->performCheck();
			} elseif (is_array($check)) {
				return $check->map(function ($check, $label) {
					if ($check instanceof StatusCheck) {
						return $check->performCheck();
					} else {
						// Don't recurse sections, for now
						return Status::$MAJOR_OUTAGE;
					}
				});
			} else {
				return Status::$MAJOR_OUTAGE;
			}
		})->toArray();
	}
}
