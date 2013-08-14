<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aramonc
 * Date: 8/14/13
 * Time: 12:00 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SmsBus\Db;


class FareRulesTable extends AbstractTable {

    protected $table = 'fare_rules';

    public function fetchAll($where = array(), $sort = '')
    {
        // TODO: Implement fetchAll() method.
    }

    public function fetch($id)
    {
        // TODO: Implement fetch() method.
    }

    public function update($data = array())
    {
        // TODO: Implement update() method.
    }

    public function save($data = array())
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function needsAgency()
    {
        // TODO: Implement needsAgency() method.
    }

    public function setAgencyId($agency_id = 0)
    {
        // TODO: Implement setAgencyId() method.
    }
}