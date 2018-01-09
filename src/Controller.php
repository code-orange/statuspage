<?php
namespace CodeOrange\Statuspage;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController {
	/** @var $statuspage Statuspage */
	private $statuspage;

	public function __construct(Statuspage $statuspage) {
		$this->statuspage = $statuspage;
	}

	public function status() {
		$status = $this->statuspage->execute();
		return view(env('STATUSPAGE_VIEW', 'statuspage::status'), ['status' => $status]);
	}
}
