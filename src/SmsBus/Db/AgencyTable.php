<?php
namespace SmsBus\Db;

use PDO;
use SmsBus\Db\AbstractTable;
use Zend\Config\Config;

class AgencyTable extends AbstractTable {
	protected $table = 'agency';
	
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
	
	public function fetchByName($name) {
		$sql = "SELECT * FROM agency WHERE agency_name = :name";
		$stmt = $this->dbConn->prepare($sql);
		$result = $stmt->execute(array(":name" => $name));
		if(!$result) {
			return false;
		}
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function importCSV(\Keboola\Csv\CsvFile $csv) {
		$csv->rewind();
		$cols = $csv->current();
		$csv->next();
		$row = $csv->current();
		$nameKey = array_search('agency_name', $cols);
		if(($exists = $this->fetchByName($row[$nameKey]))) {
			return $exists['agency_id'];
		}
		
		$sql = "INSERT INTO " . $this->table . " (" . implode(', ', $cols) . ") VALUES ";
		$insertQuery[$csv->key()] = '(:' . implode($csv->key() . ', :', $cols) . $csv->key() . ')';
		$i = 0;
		foreach ($cols as $col) {
			$insertData[":" . $col . $csv->key()] = $row[$i];
			$i++;
		}
		$sql .= implode(', ', $insertQuery);
		$stmt = $this->dbConn->prepare($sql);
		$result = $stmt->execute($insertData);
		if(!$result) {
			var_dump($sql);
			var_dump($stmt->errorInfo());
		}
		return $this->dbConn->lastInsertId();
	}
}
