<?php
namespace SmsBus\Db;

use PDO;
use Zend\Config\Config;

abstract class AbstractTable {
	protected $config;
	protected $dbConn;
	protected $table = '';
	protected $agency;

	public function __construct(Config $config = null) {
		if (!$config) {
			$config = new Config(include __DIR__ . "/../../../config/config.php");
		}

		$this->config = clone $config->db;
		$this->openConnection();
	}

	public function openConnection(Config $config = null) {
		if (!$config) {
			$config = $this->config;
		}

		$this->dbConn = new PDO($config->dsn, $config->user, $config->pass);
	}

	abstract public function fetchAll($where = array(), $sort = '');
	abstract public function fetch($id);
	abstract public function update($data = array());
	abstract public function save($data = array());
	abstract public function delete($id);
	abstract public function needsAgency();
	abstract public function setAgencyId($agency_id = 0);

	public function importCSV(\Keboola\Csv\CsvFile $csv) {
		$csv->rewind();
		$cols = $csv->current();
		$sqlDuplicate = " ON DUPLICATE KEY UPDATE";
		foreach($cols as $col) {
			$sqlDuplicate .= " " . $col . "=VALUES(" . $col . "),";
		}
		$sqlDuplicate = substr($sqlDuplicate, 0, strlen($sqlDuplicate) - 1);
		$sql = "INSERT INTO " . $this->table . " (" . implode(', ', $cols);
		if($this->needsAgency() && !in_array('agency_id', $cols) && $this->agency) {
			$sql .= ", agency_id" . ") VALUES ";
		} else {
			$sql .= ") VALUES ";
		}
		$csv->next();
		$insertQuery = array();
		$insertData = array();
		while (($row = $csv->current())) {
			$insertQuery[$csv->key()] = '(:' . implode($csv->key() . ', :', $cols) . $csv->key();
			$i = 0;
			foreach ($cols as $col) {
				$insertData[":" . $col . $csv->key()] = $row[$i];
				$i++;
			}
			if($this->needsAgency() && !in_array('agency_id', $cols) && $this->agency) {
				$insertQuery[$csv->key ()] .= ', :agency_id' . $csv->key() . ')';
				$insertData[":agency_id" . $csv->key()] = $this->agency;
			} else {
				$insertQuery[$csv->key()] .= ')';
			}
			if ($csv->key() % 200 === 0) {
				$tmpSql = $sql . implode(', ', $insertQuery) . $sqlDuplicate;
				$stmt = $this->dbConn->prepare($tmpSql);
				$result = $stmt->execute($insertData);
				$insertData = array();
				$insertQuery = array();
			}
			$csv->next();
		}
		if (!empty($insertQuery)) {
			$sql .= implode(', ', $insertQuery) . $sqlDuplicate;
			$stmt = $this->dbConn->prepare($sql);
			$result = $stmt->execute($insertData);
			if(!$result) {
				var_dump($sql);
				var_dump($this->table);
				var_dump($stmt->errorInfo());
			}
		}
		
		return $this->dbConn->lastInsertId();
	}
}
