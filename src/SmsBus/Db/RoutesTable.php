<?php
namespace SmsBus\Db;

use PDO;

class RoutesTable extends AbstractTable
{
    protected $table = 'routes';

    public function fetchAll($where = array(), $sort = '')
    {

    }

    /**
     * Retrieve a route by it's short name.
     * @param int $id
     * @return bool|mixed
     */
    public function fetch($id)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE route_short_name = :id";
        $stmt = $this->dbConn->prepare($sql);

        $resultSet = $stmt->execute(array(':id' => intval($id)));
        if(!$resultSet) {
            return false;
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
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

    /**
     * Performs a search & tries to find the route. If more than one route is returned, the first route is returned.
     * @param string $route
     * @return bool|array
     */
    public function search($route)
    {
        if (is_numeric($route)) {
            return $this->fetch($route);
        }

        // SINCE IT'S NOT NUMERIC, START SEARCHING BY NAME IN THE LONG DESCRIPTION FIELD

        $routes = null;

        // IF IT'S ONLY ONE CHARACTER APPEND A DASH FOR MORE ACCURACY
        if (strlen($route) == 1) {
            $route .= '-';
        }

        $routes = $this->searchByName($route);

        // IF THE ABOVE RETURNED NOTHING, IT'S POSSIBLE IT'S ONE OF THE ALTERNATE ROUTES, IE 72A
        if (!$routes && strlen($route) > 2) {
            $route = substr($route, 0, strlen($route) - 1);
            if(is_numeric($route)) {
                return $this->fetch(($route));
            }
        }

        // IF WE STILL DIDN'T FIND THE ROUTE THROW AN ERROR BECAUSE WE COULDN'T FIND WHAT THEY WANTED
        if ((!$routes || empty($routes))) {
            return false;
        }

        return array_shift($routes);
    }
}
