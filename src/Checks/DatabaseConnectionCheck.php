<?php
namespace CodeOrange\Statuspage\Checks;

use CodeOrange\Statuspage\Status;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;

class DatabaseConnectionCheck extends StatusCheck {
	private $connectionName;

	/**
	 * DatabaseConnectionCheck constructor.
	 * @param $connectionName
	 */
	public function __construct($connectionName = null) {
		$this->connectionName = $connectionName;
	}

	/**
	 * @return Status
	 */
	public function performCheck() {
		/** @var DatabaseManager $db */
		$db = app(DatabaseManager::class);

		try {
			$db->connection($this->connectionName)->table($db->raw('DUAL'))->select('1')->get();
		} catch (\InvalidArgumentException $e) {
			return Status::$PARTIAL_OUTAGE;
		} catch (QueryException $e) {
			return Status::$MAJOR_OUTAGE;
		}

		return Status::$OPERATIONAL;
	}
}