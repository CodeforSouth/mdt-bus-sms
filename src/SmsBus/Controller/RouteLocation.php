<?php

namespace SmsBus\Controller;

use SmsBus\Db\LocationTable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RouteLocation
{

    /**
     * @var \SmsBus\Db\LocationTable
     */
    protected $table;

    /**
     * @param LocationTable $table
     */
    public function __construct(LocationTable $table)
    {
        $this->table = $table;
    }

    /**
     * @param string $routeShort
     * @param string $tripId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction($routeShort, $tripId)
    {
        $response = new JsonResponse($this->table->fetch(['route' => $routeShort, 'trip' => $tripId]));
        return $response;
    }

    public function postAction(Request $request, $routeShort, $tripId)
    {
        $date = $request->get('created');
        if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2})\:(\d{2})\:(\d{2})/', $date) === false) {
            $date = date('Y-m-d H:i:s');
        }

        $params = [
            'route' => $routeShort,
            'trip' => $tripId,
            'lat' => floatval($request->get('lat')),
            'lng' => floatval($request->get('lng')),
            'created' => $date,
        ];

        if(!$this->table->save($params)) {
            $response = new JsonResponse(['message' => 'There was a problem persisting the location', 502]);
        } else {
            $response = $this->getAction($routeShort, $tripId);
        }

        return $response;
    }
} 