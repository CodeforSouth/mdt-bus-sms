<?php
namespace SmsBus;

use SmsBus\Db\AgencyTable;
use Zend\Feed\Reader;
use Zend\Config\Config;

class ExchangeFeedReader {
	protected $config;
	
	public function __construct(Config $config = null) {
		if(!$config) {
			$config = new Config(include __DIR__ . "/../../config/config.php");
		}
		
		$this->config = clone $config->gtfs_exchange;
	}
	
	public function consumeFeeds() {
		$today = new \DateTime();
		$files = array();
		foreach($this->config->feeds as $agency => $url) {
			echo "\nImporting : " . $url . "\n";
			try {
				$channel = Reader\Reader::import($url) ;
			} catch (\Zend\Feed\Exception\Reader\RuntimeException $e) {
    			// feed import failed
    			echo "Exception caught importing feed: {$e->getMessage()}\n";
    			exit;
			} catch (\ErrorException $e) {
				echo $e->getMessage();
			}
			if($channel->count() > 0 && $today->diff($channel->getDateModified())->d < 7) {
				$file = $this->getDataArchive($agency, $channel->current()->getEnclosure()->url);
				$dir = $this->extractData($agency, $file);
				foreach(scandir($dir) as $file){
					if(is_file($dir . '/' . $file) && strpos($file, '.txt') !== false) {
						$this->insertRecords(substr($file, 0, strpos($file, '.txt')), $dir . '/' . $file);
					}
				} 
			}
		}
	}
	
	private function insertRecords($tableName, $file) {
		$tableClass = ucfirst($tableName) . 'Table';
		$table = new $tableClass();
		var_dump($table);
		die();
		$records = new \Keboola\Csv\CsvFile($file);
	}
	
	private function getDataArchive($agency, $url) {
		set_time_limit(0); // unlimited max execution time
		$path = realpath(__DIR__ . '/../../data/') . '/' . $agency . '.zip';
		$file = fopen($path, 'w');
		$options = array(
				CURLOPT_FILE => $file,
				CURLOPT_TIMEOUT => 28800, // set this to 8 hours so we dont timeout on big files
				CURLOPT_URL => $url,
		);
		
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		if(curl_exec($ch)) {
			return $path;
		} else {
			return curl_error($ch);
		}
		return false;
	}
	
	private function extractData($agency, $zipFile) {
		$zip = new \ZipArchive();
		$dir = '';
		if ($zip->open($zipFile) === TRUE) {
			$dir = dirname($zipFile) . '/' . $agency;
			if(!is_dir($dir)) {
				mkdir(dirname($zipFile) . '/' . $agency);
			}
			$zip->extractTo($dir);
			$zip->close();
		}
		
		return $dir;
	}
}