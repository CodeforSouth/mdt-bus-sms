<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class StopsTable extends AbstractTable {
	protected $table = 'stops';

    public function fetchAllNear($lat, $lng)
    {
        $R = 3959;

        // INTERSECTION POINTS
        $lat1 = "radians(:lat)";
        $lng1 = "radians(:lng)";

        // STOP COORDS
        $lat2 = "radians(stop_lat)";
        $lng2 = "radians(stop_lon)";

        $dLng = "$lng2 - $lng1";

        $y = "sin($dLng) * cos($lat2)";
        $x = "(cos($lat1) * sin($lat2)) - (sin($lat1) * cos($lat2) * cos($dLng))";
        $bearing = "degrees(atan2($y, $x)) as bearing";
        $distance = "($R * acos((cos($lat1) * cos($lat2) * cos($lng2 - $lng1)) + (sin($lat1) * sin($lat2)))) AS distance";

        $sql = "SELECT *, $distance, $bearing FROM stops  HAVING distance < .05 ORDER BY distance LIMIT 0 , 20";
        $stmt = $this->dbConn->prepare($sql);

        $result = $stmt->execute(array(":lat" => $lat, ":lng" => $lng));
        if(!$result) {
            //Should do error logging here
            return false;
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
	
	public function fetchAll($where = array(), $sort = '') {
		
	}
	public function fetch($id) {
		
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
