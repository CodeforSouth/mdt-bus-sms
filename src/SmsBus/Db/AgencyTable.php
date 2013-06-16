<?php
namespace SmsBus\Db;

use PDO;
use Zend\Config\Config;

class AgencyTable {
	protected $config;
	protected $dbConn;
	private $table = 'agency';
	
	public function __construct(Config $config = null) {
		if(!$config) {
			$config = new Config(include __DIR__ . "/../../config/config.php");
		}
		
		$this->config = clone $config->db;
		$this->openConnection();
	}
	
	public function openConnection(Config $config = null) {
		if(!$config) {
			$config = $this->config;
		}
		
		$this->dbConn = new PDO($config->dsn, $config->user, $config->pass);
	}
	
	public function saveAgency($agency) {
	}
}
