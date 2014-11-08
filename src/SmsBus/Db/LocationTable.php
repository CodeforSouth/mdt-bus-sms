<?php

namespace SmsBus\Db;

class LocationTable extends AbstractTable
{

    public function fetchAll($where = array(), $sort = '')
    {
        // TODO: Implement fetchAll() method.
    }

    /**
     * Retrieves the location information for a specific trip of a bus route
     *
     * Required fields:
     *
     * route - string: the route short name (1, 2, 3, etc)
     * trip - integer: the trip ID number
     *
     * @param array $id
     * @return array
     */
    public function fetch($id)
    {
        if (!is_array($id) || empty($id)) {
            return array();
        }

        $sql = "SELECT * FROM `location` WHERE `route` = :route AND `trip` = :trip";
        $stmt = $this->dbConn->prepare($sql);

        if (!$stmt->execute($id)) {
            return array();
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    /**
     * Proxy to save method as that does update on duplicate key
     *
     * @param array $data
     * @return boolean
     */
    public function update($data = array())
    {
        return $this->save($data);
    }

    /**
     * Save information to the location table
     *
     * Required fields:
     *
     * route - string: the route short name (1, 2, 3, etc)
     * trip - integer: the trip ID number
     * lat - float: Latitude number
     * lng - float: Longitude number
     *
     * Optional fields:
     *
     * created - DateTime: The date and time when the latitude & longitude was read
     *
     * @param array $data
     * @return boolean
     */
    public function save($data = array())
    {
        $failure = false;
        if (empty($data)) {
            return $failure;
        }

        if (!isset($data['route'], $data['trip'], $data['lat'], $data['lng'])) {
            return $failure;
        }

        if (!isset($data['created'])) {
            $data['created'] = new \DateTime();
        }

        if (is_a($data['created'], \DateTime::class)) {
            $data['created'] = $data['created']->format('Y-m-d H:i:s');
        }

        $sql = <<<SQL
INSERT INTO `location` (route, trip, lat, lng, created)
    VALUES (:route, :trip, :lat, :lng, :created)
    ON DUPLICATE KEY UPDATE `lat` = VALUES(lat), `lng` = VALUES(lng), `created` = VALUES(created);
SQL;

        $stmt = $this->dbConn->prepare($sql);

        return (boolean)$stmt->execute($data);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * The agency field is not used by this table
     *
     * @return boolean
     */
    public function needsAgency()
    {
        return false;
    }

    public function setAgencyId($agency_id = 0)
    {
        // TODO: Implement setAgencyId() method.
    }
}