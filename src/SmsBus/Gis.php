<?php
/**
 * GIS helper functions
 * User: aramonc
 * Date: 8/3/13
 */

namespace SmsBus;


class Gis {

    // DIRECTIONS
    const NORTHBOUND = "Northbound";
    const WESTBOUND = "Westbound";
    const SOUTHBOUND = "Southbound";
    const EASTBOUND = "Eastbound";

    /**
     * Convert a negative degree into a positive one
     * @param float $degrees
     * @return int
     */
    static public function convertToPositiveDegrees($degrees)
    {
        if(bccomp($degrees, 0.0, 6) < 0) {
            $degrees += 360;
        }

        return $degrees;
    }

    static public function guessDirection($degrees)
    {
        // FIRST MAKE SURE IT'S A POSITIVE DEGREE
        $degrees = self::convertToPositiveDegrees($degrees);

        $direction = false;

        // CHECK IF NORTHBOUND ( 0 <= $degrees < 45 OR 135 <= $degrees < 180 )
        if((bccomp($degrees, 0.0, 6) >= 0 && bccomp($degrees, 45.0, 6) < 0) || (bccomp($degrees, 135.0, 6) >= 0 && bccomp($degrees, 180.0, 6) < 0)) {
            $direction = self::NORTHBOUND;
        }

        // CHECK IF WESTBOUND ( 45 <= $degrees < 90 OR 270 <= $degrees < 315 )
        if(!$direction && (bccomp($degrees, 45.0, 6) >= 0 && bccomp($degrees, 90.0, 6) < 0) || (bccomp($degrees, 270.0, 6) >= 0 && bccomp($degrees, 315.0, 6) < 0)) {
            $direction = self::WESTBOUND;
        }

        // CHECK IF SOUTHBOUND ( 180 <= $degrees < 225 OR 315 <= $degrees < 360 )
        if(!$direction && (bccomp($degrees, 180.0, 6) >= 0 && bccomp($degrees, 225.0, 6) < 0) || (bccomp($degrees, 315.0, 6) >= 0 && bccomp($degrees, 360.0, 6) < 0)) {
            $direction = self::SOUTHBOUND;
        }

        // CHECK IF EASTBOUND ( 90 <= $degrees < 135 OR 225 <= $degrees < 270 )
        if(!$direction && (bccomp($degrees, 90.0, 6) >= 0 && bccomp($degrees, 135.0, 6) < 0) || (bccomp($degrees, 225.0, 6) >= 0 && bccomp($degrees, 270.0, 6) < 0)) {
            $direction = self::EASTBOUND;
        }

        return $direction;
    }

}