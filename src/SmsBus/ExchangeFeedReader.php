<?php
namespace SmsBus;

use Zend\Feed\Reader;
use Zend\Config\Config;

class ExchangeFeedReader
{
    /**
     * @var \Zend\Config\Config
     */
    protected $config;
    /**
     * @var int
     */
    protected $agency = 0;

    public function __construct(Config $config = null)
    {
        if (!$config) {
            $config = new Config(include realpath(__DIR__ . "/../../config/config.php"));
        }

        $this->config = clone $config->gtfs_exchange;
    }

    /**
     * Consumes the list of RSS Feeds, downloads and extracts GTFS zip archives, and inserts the data into the appropriate database table
     */
    public function consumeFeeds()
    {
        $today = new \DateTime();
        foreach ($this->config->feeds as $agency => $url) {
            echo "\nImporting : " . $url . "\n";
            try {
                $channel = Reader\Reader::import($url);
            } catch (\Zend\Feed\Exception\Reader\RuntimeException $e) {
                // feed import failed
                echo "Exception caught importing feed: {$e->getMessage()}\n";
                exit;
            } catch (\ErrorException $e) {
                echo $e->getMessage();
            }
            if ($channel->count() > 0 && $today->diff($channel->getDateModified())->d < 7) {
                $file = $this->getDataArchive($agency, $channel->current()->getEnclosure()->url);
                $dir = $this->extractData($agency, $file);

                // IMPORT TABLES WITHOUT DEPENDENCIES
                // IMPORT THE AGENCY TABLE
                echo "Importing the AGENCY table. ";
                $this->agency = $this->insertRecords('agency', $dir . '/agency.txt', null);

                // IMPORT THE STOPS TABLE
                echo "Importing the STOPS table. ";
                $this->agency = $this->insertRecords('stops', $dir . '/stops.txt', null);

                // IMPORT THE SHAPES TABLE
                echo "Importing the SHAPES table. ";
                $this->insertRecords('shapes', $dir . '/shapes.txt', $this->agency);

                // IMPORT DEPENDENT TABLES
                // IMPORT THE FEED INFO TABLE
                echo "Importing the FEED INFO table. ";
                $this->insertRecords('feed_info', $dir . '/feed_info.txt', $this->agency);

                // IMPORT THE ROUTES TABLE
                echo "Importing the ROUTES table. ";
                $this->insertRecords('routes', $dir . '/routes.txt', $this->agency);

                // IMPORT THE TRIPS TABLE
                echo "Importing the TRIPS table. ";
                $this->insertRecords('trips', $dir . '/trips.txt', $this->agency);

                // IMPORT THE FARE RULES TABLE
                echo "Importing the FARE RULES table. ";
                $this->insertRecords('fare_rules', $dir . '/fare_rules.txt', $this->agency);

                // IMPORT THE FARE ATTRIBUTES TABLE
                echo "Importing the FARE ATTRIBUTES table. ";
                $this->insertRecords('fare_attributes', $dir . '/fare_attributes.txt', $this->agency);

                // IMPORT THE FREQUENCIES TABLE
                echo "Importing the FREQUENCIES table. ";
                $this->insertRecords('fare_rules', $dir . '/frequencies.txt', $this->agency);

                // IMPORT THE CALENDAR TABLE
                echo "Importing the CALENDAR table. ";
                $this->insertRecords('calendar', $dir . '/calendar.txt', $this->agency);

                // IMPORT THE CALENDAR DATES TABLE
                echo "Importing the CALENDAR DATES table. ";
                $this->insertRecords('calendar_dates', $dir . '/calendar_dates.txt', $this->agency);

                // IMPORT THE TRANSFERS TABLE
                echo "Importing the TRANSFERS table. ";
                $this->insertRecords('transfers', $dir . '/transfers.txt', $this->agency);

                // IMPORT THE STOP TIMES TABLE
                echo "Importing the STOP TIMES table. ";
                $this->insertRecords('stop_times', $dir . '/stop_times.txt', $this->agency);
            }
        }
    }

    /**
     * @param  string $tableName
     * @param  string $file
     * @param  int    $agency_id
     * @return mixed
     */
    private function insertRecords($tableName, $file, $agency_id)
    {
        if (!is_file($file)) {
            return false;
        }
        $now = new \DateTime();
        $tableName = $this->toCamelCase($tableName, true);
        $tableClass = '\\SmsBus\\Db\\' . ucfirst($tableName) . 'Table';
        $table = new $tableClass();
        if ($agency_id && $table->needsAgency()) {
            $table->setAgencyId($agency_id);
        }
        $result = $table->importCSV(new \Keboola\Csv\CsvFile($file));
        echo "Time elapsed: " . $this->getTimeDiff($now) . "\n";

        return $result;
    }

    /**
     * Downloads a GTFS archive from a URL using Curl
     * @param  string $agency
     * @param  string $url
     * @return string The path the GTFS archive was saved to or the Curl error
     */
    private function getDataArchive($agency, $url)
    {
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
        if (!curl_exec($ch)) {
            return curl_error($ch);
        }

        return $path;
    }

    /**
     * Extract files from a Zip archive
     * @param  string $agency
     * @param  string $zipFile
     * @return string
     */
    private function extractData($agency, $zipFile)
    {
        $zip = new \ZipArchive();
        $dir = '';
        if ($zip->open($zipFile) === true) {
            $dir = dirname($zipFile) . '/' . $agency;
            if (!is_dir($dir)) {
                mkdir(dirname($zipFile) . '/' . $agency);
            }
            $zip->extractTo($dir);
            $zip->close();
        }

        return $dir;
    }

    /**
     * Converts a string with under_scores to camelCase
     * @param  string $str
     * @param  bool   $capitalise_first_char Converts to full CamelCase if true
     * @return mixed
     */
    protected function toCamelCase($str, $capitalise_first_char = false)
    {
        if ($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * Return the difference between a start time and the current time in minutes
     * @param  \DateTime $start
     * @return string
     */
    protected function getTimeDiff(\DateTime $start)
    {
        $now = new \DateTime();
        $diff = $now->diff($start);

        return (($diff->h * 60) + $diff->i) + round(($diff->s / 60), 3) . " mintues";
    }
}
