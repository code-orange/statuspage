<?php
namespace CodeOrange\Statuspage;
use CodeOrange\Statuspage\Checks\InTimeCheck;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Factory;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController {
	/** @var $statuspage Statuspage */
	private $statuspage;
	/** @var $cache CacheManager */
	private $cache;

	public function __construct(Statuspage $statuspage, Factory $cache) {
		$this->statuspage = $statuspage;
		$this->cache = $cache->store();
	}

	private function getCurrentStatus() {
		if ($this->cache->has('statuspage_status')) {
			$status = $this->cache->get('statuspage_status');
			// Prepend time status check
			return ['Status check data is recent' => (new InTimeCheck($status['timestamp'], '-1 minute', '-5 minutes'))->performCheck()] + $status['results'];
		} else {
			return $this->statuspage->execute();
		}
	}

	public function status() {
		$status = $this->getCurrentStatus();
		return view(env('STATUSPAGE_VIEW', 'statuspage::status'), ['status' => $status]);
	}

	public function json() {
		return response()->json($this->getCurrentStatus());
	}
}
