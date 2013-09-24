<?php
/**
 * Controller definition for the series of stop commands.
 * User: aramonc
 * Date: 7/27/13
 */

namespace SmsBus\Controller;

use ApiConsumer\Consumer;
use Silex\Application;
use SmsBus\Db\StopsTable;
use SmsBus\Gis;
use Zend\Config\Config;
use Zend\I18n\Translator\Translator;

class Stop
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
    public function getStop()
    {
        $translator = $this->translator;
        $stopBus = $this->app['controllers_factory'];
        $stopBus->get('/{stopId}', function ($stopId) use ($translator) {

            $stopTable = new StopsTable();

            $stop = $stopTable->fetch($stopId);
            if(!$stop) {
                return $this->translator->translate("There was an error fetching the stop information");
            }

            $stop = array_shift($stop);

            return "http://maps.google.com/?q=" . $stop['stop_lat'] . ',' . $stop['stop_lon'];
        })->convert('stopId', array($this, 'intProvider'));
        return $stopBus;
    }

    public function getStops()
    {
        $translator = $this->translator;
        $config = $this->config;
        $stops = $this->app['controllers_factory'];
        $stops->get('/location/{address1}/{address2}/{city}/{state}', function ($address1, $address2, $city = null, $state = null) use ($translator, $config) {
            // PREPARE ARCGIS API REQUEST
            $url = 'http://www.mapquestapi.com/geocoding/v1/address';

            $location = $address1 . ' at ' . $address2;
            $location .= isset($city) ? ', ' . $city : '' ;
            $location .= isset($city) && isset($state) ? ', ' . $state : '';

            $params = array(
                'key' => $config->mapquest->app_key,
                'location' => urlencode($location),
                'boundingBox' => '26.172906,-80.781097,25.267266,-79.990082', // BOUNDING BOX COVERS ALL OF DADE COUNTY DOWN TO THE DAGNY JOHNSON KEY
                'maxResults' => 3,
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
                $message = "Could not find stops at " . $location;
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

