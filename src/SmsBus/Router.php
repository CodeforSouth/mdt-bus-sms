<?php
/**
 * Figures out which method to run based on the message received
 *
 * @package SmsBus
 * @author Adrian Cardenas <arcardenas@gmail.com>
 */
namespace SmsBus;


class Router {

    protected $commands;

    /**
     * Constructs the command patterns & callbacks used to create the route.
     * Patterns have to be listed from most specific to least specific
     */
    public function __construct()
    {
        $this->commands = array(
            // MATCHES 'bus # at #'
            '/\bbus\s\b([0-9]{1,3}[a-zA-Z]?|[a-zA-Z])\b\sat\s\b([0-9]{1,6})/' => function(array $match) {
                return '/bus/' . $match[1] . '/at/' . $match[2];
            },
            // MATCHES 'bus #'
            '/\bbus\s\b([0-9]{1,3}[a-zA-Z]?|[a-zA-Z])/' => function(array $match) {
                return '/bus/' . $match[1];
            }
        );
    }

    /**
     * Iterates through the command patterns list until it finds a match & then constructs the command route
     * @param $message
     * @return string
     */
    public function getRoute($message)
    {
        $route = '';
        if(empty($message)) {
            return $route;
        }

        foreach($this->commands as $pattern => $command) {
            $matched = preg_match($pattern, $message, $match);
            if($matched > 0 ) {
                $route = $command($match);
                break;
            }
        }

        return $route;
    }

} 