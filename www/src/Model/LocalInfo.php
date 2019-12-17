<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 31/12/17
 * Time: 04:48
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class LocalInfo implements JsonSerializable
{
    private $latitude;
    private $longitude;

    /**
     * LocalInfo constructor.
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
        $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $value = new stdClass();
        $value->latitude = $this->getLatitude();
        $value->longitude = $this->getLongitude();
        return $value;
    }

    /**
     * @param stdClass $value
     * @return LocalInfo
     */
    public static function fromJson($value) {
        return new LocalInfo($value->latitude, $value->longitude);
    }
}