<?php

namespace Khill\Lavacharts\DataTables\Cells;

use \Carbon\Carbon;
use \Khill\Lavacharts\Utils;
use \Khill\Lavacharts\Exceptions\FailedCarbonParsing;
use \Khill\Lavacharts\Exceptions\InvalidDateTimeString;

/**
 * DateCell Class
 *
 * Wrapper object to implement JsonSerializable on the Carbon object.
 *
 * @package    Khill\Lavacharts
 * @subpackage DataTables
 * @since      3.0.0
 * @author     Kevin Hill <kevinkhill@gmail.com>
 * @copyright  (c) 2015, KHill Designs
 * @link       http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link       http://lavacharts.com                   Official Docs Site
 * @license    http://opensource.org/licenses/MIT MIT
 */
class DateCell extends Cell
{
    /**
     * Creates a new DateCell object from a Carbon object.
     *
     * @param Carbon $carbon
     */
    public function __construct(Carbon $carbon)
    {
        parent::__construct($carbon);
    }

    /**
     * @param  string $dateTimeString
     * @param  string $dateTimeFormat
     * @return \Khill\Lavacharts\DataTables\DateCell
     * @throws \Khill\Lavacharts\Exceptions\FailedCarbonParsing
     * @throws \Khill\Lavacharts\Exceptions\InvalidDateTimeString
     */
    public static function parseString($dateTimeString, $dateTimeFormat)
    {
        if (Utils::nonEmptyString($dateTimeString) === false) {
            throw new InvalidDateTimeString($dateTimeString);
        }

        try {
            if (Utils::nonEmptyString($dateTimeFormat) === true) {
                $carbon = Carbon::createFromFormat($dateTimeFormat, $dateTimeString);
            } else {
                $carbon = Carbon::parse($dateTimeString);
            }
        } catch (\Exception $e) {
            throw new FailedCarbonParsing($dateTimeString);
        }

        return new DateCell($carbon);
    }

    /**
     * Custom string output of the Carbon date.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'Date(%d,%d,%d,%d,%d,%d)',
            isset($this->v->year)   ? $this->v->year      : 'null',
            isset($this->v->month)  ? $this->v->month - 1 : 'null', //silly javascript
            isset($this->v->day)    ? $this->v->day       : 'null',
            isset($this->v->hour)   ? $this->v->hour      : 'null',
            isset($this->v->minute) ? $this->v->minute    : 'null',
            isset($this->v->second) ? $this->v->second    : 'null'
        );
    }

    /**
     * Custom serialization of the Carbon date.
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return (string) $this;
    }
}