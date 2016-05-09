<?php

namespace Khill\Lavacharts\Dashboards\Bindings;

use Khill\Lavacharts\Dashboards\Wrappers\ChartWrapper;
use Khill\Lavacharts\Dashboards\Wrappers\ControlWrapper;
use Khill\Lavacharts\Dashboards\Wrappers\Wrapper;
use Khill\Lavacharts\Exceptions\InvalidBindings;

/**
 * BindingFactory Class
 *
 * Creates new bindings for dashboards.
 *
 * @package   Khill\Lavacharts\Dashboards\Bindings
 * @since     3.0.0
 * @author    Kevin Hill <kevinkhill@gmail.com>
 * @copyright (c) 2016, KHill Designs
 * @link      http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link      http://lavacharts.com                   Official Docs Site
 * @license   http://opensource.org/licenses/MIT      MIT
 */
class BindingFactory
{
    /**
     * Create a new Binding for the dashboard.
     *
     * @param  mixed $controlWraps One or array of many ControlWrappers
     * @param  mixed $chartWraps   One or array of many ChartWrappers
     * @throws \Khill\Lavacharts\Exceptions\InvalidBindings
     * @return \Khill\Lavacharts\Dashboards\Bindings\Binding
     */
    public static function create($controlWraps, $chartWraps)
    {
        if ($controlWraps instanceof ControlWrapper &&
            $chartWraps instanceof ChartWrapper
        ) {
            return new OneToOne($controlWraps, $chartWraps);
        }

        if ($controlWraps instanceof ControlWrapper &&
            self::isArrayOfWrappers($chartWraps)
        ) {
            return new OneToMany($controlWraps, $chartWraps);
        }

        if (self::isArrayOfWrappers($controlWraps) &&
            $chartWraps instanceof ChartWrapper
        ) {
            return new ManyToOne($controlWraps, $chartWraps);
        }

        if (self::isArrayOfWrappers($controlWraps) &&
            self::isArrayOfWrappers($chartWraps)
        ) {
            return new ManyToMany($controlWraps, $chartWraps);
        }

        throw new InvalidBindings;
    }

    /**
     * @param  array $bindings
     * @return array Array of bindings
     */
    public static function createFromArray(array $bindings)
    {
        return array_map(function ($binding) {
            return self::create($binding[0], $binding[1]);
        }, $bindings);
    }

    private static function isArrayOfWrappers(array $array)
    {
        return array_reduce($array, function ($prev, $curr) {
            return $prev && is_subclass_of(
                $curr,
                '\Khill\Lavacharts\Dashboards\Wrappers\Wrapper'
            );
        }, true);
    }
}
