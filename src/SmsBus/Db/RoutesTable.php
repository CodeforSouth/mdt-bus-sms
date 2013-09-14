<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class RoutesTable extends AbstractTable {
	protected $table = 'routes';
	
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
		return true;
	}
	
	public function setAgencyId($agency_id = 0) {
		$this->agency = intval($agency_id);
	}

    /**
     * Searches the route_long_name field for matching terms to return the desired route(s).
     * @param $phrase
     * @return array|bool
     */
    public function searchByName($phrase)
    {
        $terms = explode(" ", $phrase);
        $search_terms = array();
        foreach($terms as $i => $term) {
            $search_terms[':route_' . $i] = '%' . $term . '%';
        }

        $sql = "SELECT * FROM `routes` WHERE ";
        $sql .= "`route_long_name` LIKE " . implode(" AND `route_long_name` LIKE ", array_keys($search_terms)) . "";

        $stmt = $this->dbConn->prepare($sql);

        $result = $stmt->execute($search_terms);
        if(!$result) {
            return false;
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
