<?php
namespace CodeOrange\Statuspage\Checks;

use CodeOrange\Statuspage\Status;
use Illuminate\Contracts\Cache\Factory;

class CacheConnectionCheck extends StatusCheck {
	/** @var string $storename */
	private $storename;
	/** @var boolean $verbose */
	private $verbose;

	/**
	 * CacheConnectionCheck constructor.
	 * @param string $storename
	 * @param bool $verbose If the return status should be verbose (includes connection name)
	 */
	public function __construct($storename = null, $verbose = true) {
		$this->storename = $storename;
		$this->verbose = $verbose;
	}

	/**
	 * @return Status
	 */
	public function performCheck() {
		/** @var Factory $cacheFactory */
		$cacheFactory = app(Factory::class);

		try {
			$cache = $cacheFactory->store($this->storename);
		} catch (\InvalidArgumentException $e) {
			return $this->verbose ? Status::$PARTIAL_OUTAGE->withExplanation("Cache connection {$this->storename} not found") : Status::$PARTIAL_OUTAGE;
		}

		try {
			$cache->has('test');
		} catch (\Exception $e) {
			// All drivers have different exceptions that don't have a common ancestor, so we just catch them all
			return $this->verbose ? Status::$MAJOR_OUTAGE->withExplanation('Database not reachable') : Status::$MAJOR_OUTAGE;
		}

		return Status::$OPERATIONAL;
	}
}