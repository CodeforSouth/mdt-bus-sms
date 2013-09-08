<?php
/**
 * Figures out which method to run based on the message received
 *
 * @package SmsBus
 * @author Adrian Cardenas <arcardenas@gmail.com>
 */
namespace SmsBus;


class Router {

    public function getRoute($message)
    {
        $route = '';
        if(empty($message)) {
            return $route;
        }


    }

} 