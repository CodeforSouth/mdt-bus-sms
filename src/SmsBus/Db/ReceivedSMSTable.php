<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class ReceivedSMSTable extends AbstractTable {
	protected $table = 'logs';
	protected $error = '';
	
	public function fetchAll($where = array(), $sort = '') {
		
	}
	public function fetch($id) {
		
	}
	public function update($data = array()) {
		
	}
	public function save($data = array()) {
		if(empty($data)) {
			return false;
		}
		$newData = array();
		foreach($data as $key => $val) {
			$newData[strtolower($key)] = $val;
		}
		$sql = "INSERT INTO " . $this->table . " (`" . implode("`, `", array_keys($newData)) . "`) VALUES (:" . implode(", :", array_keys($newData)) . ")";
		$stmt = $this->dbConn->prepare($sql);
		$result = $stmt->execute($newData);
		if(!$result) {
			$arr = $stmt->errorInfo();
			$this->error = $arr[2];
			return false;
		}
		return $this->dbConn->lastInsertId();
	}
	public function delete($id) {
		
	}
	
	public function needsAgency() {
		return false;
	}
	
	public function setAgencyId($agency_id = 0) {
		
	}
	
	public function getError() {
		return $this->error;
	}
}
