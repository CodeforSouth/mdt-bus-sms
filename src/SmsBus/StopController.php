<?php
/**
 * Controller definition for the series of stop commands.
 * User: aramonc
 * Date: 7/27/13
 */

namespace SmsBus;

use ApiConsumer\Consumer;
use Silex\Application;
use SmsBus\Db\StopsTable;
use SmsBus\Db\StopTimesTable;
use Zend\Config\Config;
use Zend\I18n\Translator\Translator;

class StopController
{
    private $app;
    private $translator;
    private $config;

    public function __construct(Application $app, Config $config, Translator $translator)
    {
        $this->app = $app;
        $this->translator = $translator;
        $this->config = $config;
    }

    /**
     * The controller definition for the stop bus controller
     * @return mixed
     */
    public function getStopBusController()
    {
        $translator = $this->translator;
        $stopBus = $this->app['controllers_factory'];
        $stopBus->get('/{stopId}/{bus}/{busId}', function ($locale, $stopId, $bus, $busId) use ($translator) {

            // PREPARE THE TRANSLATOR
            $translator->setLocale($locale);

            // INSTANTIATE THE TIMES TABLE MAPPER
            $timesTable = new StopTimesTable();

            // GET THE NEXT 3 TIMES THE BUS WILL ARRIVE AT THE STOP
            if ($bus) {
                $stopTimes = $timesTable->fetchByBusStop($stopId, $busId);
            } else {
                $stopTimes = $timesTable->fetchByStop($stopId);
            }

            // INSTANTIATE THE DEFAULTS
            $times = array();

            if (!$stopTimes) {
                // NOTHING WAS RETURNED SO RETURN AN ERROR
                $message = $translator->translate("There was an error fetching the stop times");
            } else if (is_array($stopTimes) && count($stopTimes) > 0) {
                // FORMAT THE TIMES TO RETURN
                foreach ($stopTimes as $stop_time) {
                    $now = new \DateTime();

                    $time = $now->diff(\DateTime::createFromFormat("H:i:s", $stop_time['arrival_time']));

                    if ($bus) {
                        $times[] = $time->h . ":" . $time->i . ":" . $time->s;
                    } else {
                        $times[] = $translator->translate('Bus') . ' ' . $stop_time['route_short_name'] . ': ' . $time->h . ":" . $time->i . ":" . $time->s;
                    }
                }

                $message = implode(', ', $times);
            } else {
                // NO TIMES WERE RETURNED SO THE BUS WILL NOT ARRIVE AT THE STOP ANY ORE TODAY
                $message = $translator->translate("Bus") . " " . $busId . " " . $translator->translate("will not stop at") . " " . $stopId . " " . $translator->translate("any more today");
            }

            return $message;
        })
            ->convert('stopId', array($this, 'intProvider'))
            ->convert('busId', array($this, 'intProvider'))
            ->value('locale', 'en-US')
            ->value('bus', false)
            ->value('busId', 0);
        return $stopBus;
    }

    public function getStops()
    {
        $translator = $this->translator;
        $config = $this->config;
        $stops = $this->app['controllers_factory'];
        $stops->get('/{address}', function ($locale, $address) use ($translator, $config) {
            // PREPARE THE TRANSLATOR
            $translator->setLocale($locale);

            // PREPARE ARCGIS API REQUEST
            $url = 'http://www.mapquestapi.com/geocoding/v1/address';
            $params = array(
                'key' => $config->mapquest->app_key,
                'location' => $address,
                'boundingBox' => '26.172906,-80.781097,25.267266,-79.990082', // BOUNDING BOX COVERS ALL OF DADE COUNTY DOWN TO THE DAGNY JOHNSON KEY
                'maxResults' => 1,
            );

            $consumer = new Consumer();
            $consumer->setUrl($url);
            $consumer->setParams($params);
            $consumer->setOptions(array());
            $consumer->setResponseType('json');
            $consumer->setCallType('get');

            // GET ADDRESS GEOLOCATION
            $result = $consumer->doApiCall();

            $coords = $result['results'][0]['locations'][0]['latLng'];

            $stopsTable = new StopsTable();
            $stops = $stopsTable->fetchAllNear($coords['lat'], $coords['lng']);

            $stopDirections = array();
            foreach($stops as $stop) {
                $stopDirections[] = $translator->translate(Gis::guessDirection($stop['bearing'])) . " " . $translator->translate('stop') . " # " . $stop['stop_id'];
            }

            $message = '';
            if(count($stopDirections) > 0) {
                $message = implode(', ', $stopDirections);
            } else {
                $message = "Could not find stops at " . urldecode($address);
            }

            return $message;

        });

        return $stops;
    }

    /**
     * @param $number
     * @return int
     */
    public function intProvider($number)
    {
        return intval($number);
    }
}

