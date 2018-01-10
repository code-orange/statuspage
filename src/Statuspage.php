<?php
namespace CodeOrange\Statuspage;

use Carbon\Carbon;
use CodeOrange\Statuspage\Checks\StatusCheck;
use Illuminate\Cache\CacheManager;
use Illuminate\Console\Scheduling\Schedule;

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
				return collect($check)->map(function ($check, $label) {
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

	/**
	 * If you call this function from your Kernel's schedule function, background execution of the registered checks will be scheduled
	 *
	 * @param Schedule $s
	 */
	public function scheduleBackgroundExecution(Schedule $s) {
		$s->call(function () {
			// Re-grab the Statuspage instance from the container instead of passing it to the closure
			/** @var self $statuspage */
			$statuspage = app()->make(self::class);
			$results = $statuspage->execute();

			/** @var CacheManager $cache */
			$cache = app()->make('cache');
			$cache->forever('statuspage_status', [
				'timestamp' => Carbon::now(),
				'results' => $results
			]);
		})->everyMinute();
	}
}
