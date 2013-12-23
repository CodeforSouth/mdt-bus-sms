<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class StopsTable extends AbstractTable {
	protected $table = 'stops';

    public function fetchAllNear($lat, $lng)
    {
        $sql = "SELECT *, haversine(stop_lat, stop_lon, :lat, :lng) AS distance, bearing(stop_lat, stop_lon, :lat, :lng) as bearing FROM stops  HAVING distance < 0.05 ORDER BY distance LIMIT 0 , 20";
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

    /**
     * Retrieves one stop location by it's ID
     * @param int $id
     * @return array|bool
     */
    public function fetch($id) {
        if(intval($id) == 0) {
            return false;
        }
		$sql = "SELECT * FROM stops WHERE stop_id = :id";
        $stmt = $this->dbConn->prepare($sql);

        $result = $stmt->execute(array(':id' => intval($id)));
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
