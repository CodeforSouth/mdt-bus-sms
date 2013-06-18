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
}
