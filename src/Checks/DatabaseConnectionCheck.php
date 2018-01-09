<?php
namespace CodeOrange\Statuspage\Checks;

use CodeOrange\Statuspage\Status;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;

class DatabaseConnectionCheck extends StatusCheck {
	private $connectionName;
	private $verbose;

	/**
	 * DatabaseConnectionCheck constructor.
	 * @param $connectionName
	 * @param bool $verbose If the return status should be verbose (includes connection name)
	 */
	public function __construct($connectionName = null, $verbose = true) {
		$this->connectionName = $connectionName;
		$this->verbose = $verbose;
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
			return $this->verbose ? Status::$PARTIAL_OUTAGE->withExplanation("Database connection {$this->connectionName} not found") : Status::$PARTIAL_OUTAGE;
		} catch (QueryException $e) {
			return $this->verbose ? Status::$MAJOR_OUTAGE->withExplanation('Database not reachable') : Status::$MAJOR_OUTAGE;
		}

		return Status::$OPERATIONAL;
	}
}