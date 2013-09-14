<?php
namespace SmsBus\Db;

use PDO;

class RoutesTable extends AbstractTable
{
    protected $table = 'routes';

    public function fetchAll($where = array(), $sort = '')
    {

    }

    public function fetch($id)
    {

    }

    public function update($data = array())
    {

    }

    public function save($data = array())
    {

    }

    public function delete($id)
    {

    }

    public function needsAgency()
    {
        return true;
    }

    public function setAgencyId($agency_id = 0)
    {
        $this->agency = intval($agency_id);
    }

    /**
     * Searches the route_long_name field for matching terms to return the desired route(s).
     * @param $phrase
     * @return array|bool
     */
    public function searchByName($phrase)
    {
        $terms = explode(" ", $phrase);
        $search_terms = array();
        foreach ($terms as $i => $term) {
            $search_terms[':route_' . $i] = '%' . $term . '%';
        }

        $sql = "SELECT * FROM `routes` WHERE ";
        $sql .= "`route_long_name` LIKE " . implode(" AND `route_long_name` LIKE ", array_keys($search_terms)) . "";

        $stmt = $this->dbConn->prepare($sql);

        $result = $stmt->execute($search_terms);
        if (!$result) {
            return false;
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumericRouteId($route_id)
    {
        if (is_numeric($route_id)) {
            return $route_id;
        }

        // SINCE IT'S NOT NUMERIC, START SEARCHING BY NAME IN THE LONG DESCRIPTION FIELD

        $routes = null;

        // IF IT'S ONLY ONE CHARACTER APPEND A DASH FOR MORE ACCURACY
        if (strlen($route_id) == 1) {
            $route_id .= '-';
        }

        $routes = $this->searchByName($route_id);

        // IF THE ABOVE RETURNED NOTHING, IT'S POSSIBLE IT'S ONE OF THE ALTERNATE ROUTES, IE 72A
        $route_id = substr($route_id, 0, strlen($route_id) - 1);;
        if (!$routes && strlen($route_id) > 2 && is_numeric($route_id)) {
            return $route_id;
        }

        // IF WE STILL DIDN'T FIND THE ROUTE THROW AN ERROR BECAUSE WE COULDN'T FIND WHAT THEY WANTED
        if ((!$routes || count($routes) == 0) && !is_numeric($route_id)) {
            return false;
        }

        return 0;
    }
}
