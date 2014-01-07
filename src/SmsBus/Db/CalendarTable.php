<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class CalendarTable extends AbstractTable {
	protected $table = 'calendar';
	
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
