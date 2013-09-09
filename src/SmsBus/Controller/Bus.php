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
            $response = 'Bus ';
            $routesTable = new RoutesTable();

            // IF IT'S ONLY ONE CHARACTER APPEND A DASH FOR MORE ACCURACY
            if (strlen($bus_id) == 1) {
                $bus_id .= '-';
            }
            // START WITH THE LONG DESCRIPTION
            $routes = $routesTable->searchByName($bus_id);


            return $response;
        });

        return $busAction;
    }

    public function getBusArrivalAction()
    {
        $translator = $this->translator;
        $config = $this->config;
        $busAction = $this->app['controllers_factory'];
        $busAction->get('/{bus_id}/at/{stop_id}', function ($bus_id, $stop_id) use ($translator, $config) {
            return $bus_id . ' ' . $stop_id;
        });

        return $busAction;
    }
} 