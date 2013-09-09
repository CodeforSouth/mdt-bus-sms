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

    public function __construct()
    {
        $this->commands = array(
            '/\bbus\s\b([0-9]{1,3}[a-zA-Z]?|[a-zA-Z])\b\sat\s\b([0-9]{1,6})/' => function(array $match) {
                return '/bus/' . $match[1] . '/at/' . $match[2];
            },
        );
    }

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