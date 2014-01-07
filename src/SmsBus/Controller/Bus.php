<?php
/**
 * Created by PhpStorm.
 * User: aramonc
 * Date: 9/8/13
 * Time: 8:45 PM
 */

namespace SmsBus\Controller;


use Silex\Application;
use SmsBus\Db\RoutesTable;
use SmsBus\Db\StopTimesTable;
use Zend\Config\Config;
use Zend\I18n\Translator\Translator;

class Bus
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

    public function getBusAction()
    {
        $translator = $this->translator;
        $config = $this->config;
        $busAction = $this->app['controllers_factory'];

        $busAction->get('/{bus_id}', function ($bus_id) use ($translator, $config) {
            $routesTable = new RoutesTable();

            // MAKE SURE THE BUS ID IS THE SHORT DESCRIPTION
            $bus = $routesTable->search($bus_id);

            if(!$bus) {
                return $translator->translate("There was an error fetching the route information", 'smsbus');
            }

            $result = ' ' . $bus['route_short_name'] . ' - ' . $bus['route_long_name'];
            $words = array();
            foreach(explode(" ", $result) as $word) {
                $words[] = $translator->translate($word, 'smsbus');
            }

            return implode(" ", $words);
        });

        return $busAction;
    }

    public function getBusArrivalAction()
    {
        $translator = $this->translator;
        $config = $this->config;
        $busAction = $this->app['controllers_factory'];
        $busAction->get('/{bus_id}/at/{stop_id}', function ($bus_id, $stop_id) use ($translator, $config) {
            $routeTable = new RoutesTable();
            $stopTimesTable = new StopTimesTable();

            $bus = $routeTable->search($bus_id);
            if(!$bus) {
                return $translator->translate("There was an error fetching the route information", 'smsbus');
            }

            $stopTimes = $stopTimesTable->fetchByBusStop($stop_id, $bus['route_short_name']);
            if(!$stopTimes) {
                return $translator->translate("There was an error fetching the stop times");
            }

            if(empty($stopTimes)) {
                // NO TIMES WERE RETURNED SO THE BUS WILL NOT ARRIVE AT THE STOP ANY ORE TODAY
                return $translator->translate("Bus") . " " . $bus_id . " " . $translator->translate("will not stop at") . " " . $stop_id . " " . $translator->translate("any more today");
            }

            $times = array();
            foreach($stopTimes as $stopTime) {
                $now = new \DateTime();
                $time = $now->diff(\DateTime::createFromFormat("H:i:s", $stopTime['arrival_time']));

                $times[] = $time->h * 60 + $time->i + round($time->s / 60, 2) . " mins";
            }

            $response = $translator->translate("Arrives") . " ";
            $response .= $translator->translate("at") . " ";
            $response .= implode(", ", $times);

            return $response;

        })->convert('stop_id', array($this, 'intProvider'));

        return $busAction;
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