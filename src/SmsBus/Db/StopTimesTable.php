<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class StopTimesTable extends AbstractTable {
	protected $table = 'stop_times';
	
	public function fetchAll($where = array(), $sort = '') {
		
	}
	public function fetch($id) {
		
	}
	
	public function fetchByBusStop($stop = 0, $bus = 0) {
		if(intval($stop) === 0 || intval($bus) === 0) {
			return false;
		}
		
		$now = new \DateTime();
		$day = strtolower($now->format('l'));				
		$sql = "SELECT `stop_times`.*, `routes`.* FROM `stop_times` LEFT JOIN `trips` ON `stop_times`.`trip_id` = `trips`.`trip_id` LEFT JOIN `routes` ON `trips`.`route_id` = `routes`.`route_id` LEFT JOIN `calendar` ON `calendar`.`service_id`=`trips`.`service_id` WHERE `calendar`.`" . $day . "` = 1 AND `stop_times`.`stop_id` = :stop_id AND `routes`.`route_short_name` = :bus_id AND `stop_times`.`arrival_time` > '" . $now->format('H:i:s') . "' ORDER BY `stop_times`.`arrival_time` ASC LIMIT 0, 3";
		$stmt = $this->dbConn->prepare($sql);
		$result = $stmt->execute(array(":stop_id" => $stop, ":bus_id" => $bus));
		if(!$result) {
			//Should do error logging here
			return false;
		}
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	public function update($data = array()) {
		
	}
	public function save($data = array()) {
		
	}
	public function delete($id) {
		
	}
	
	public function needsAgency() {
		return false;
	}
	
	public function setAgencyId($agency_id = 0) {
		
	}
}
