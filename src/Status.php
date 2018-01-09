<?php
namespace CodeOrange\Statuspage;

use JsonSerializable;

class Status implements JsonSerializable {
	/** @var $status integer */
	private $status;

	private function __construct($status) {
		$this->status = $status;
	}

	public function __toString() {
		switch ($this->status) {
			case 0:
				return 'Operational';
			case 1:
				return 'Under maintenance';
			case 2:
				return 'Degraded performance';
			case 3:
				return 'Partial outage';
			case 4:
				return 'Major outage';
			default:
				return '?';
		}
	}

	public function color() {
		switch ($this->status) {
			case 0:
				return '8BC34A';
			case 1:
				return 'FBC02D';
			case 2:
				return 'FF9800';
			case 3:
				return 'F57C00';
			case 4:
				return 'E65100';
			default:
				return '000000';
		}
	}

	/** @var $OPERATIONAL Status */
	static $OPERATIONAL;
	/** @var $MAINTENANCE Status */
	static $MAINTENANCE;
	/** @var $DEGRADED Status */
	static $DEGRADED;
	/** @var $PARTIAL_OUTAGE Status */
	static $PARTIAL_OUTAGE;
	/** @var $MAJOR_OUTAGE Status */
	static $MAJOR_OUTAGE;

	public static function init() {
		self::$OPERATIONAL = new self(0);
		self::$MAINTENANCE = new self(1);
		self::$DEGRADED = new self(2);
		self::$PARTIAL_OUTAGE = new self(3);
		self::$MAJOR_OUTAGE = new self(4);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize() {
		return (string)$this;
	}
}
Status::init();
