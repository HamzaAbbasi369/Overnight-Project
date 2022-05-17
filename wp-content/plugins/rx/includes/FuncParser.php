<?php

class FuncParser
{
    private static $func_parameters;

    public static function getFuncArgs($name)
    {
        if (empty(self::$func_parameters[ $name ])) {
            $function                        = new ReflectionFunction($name);
            self::$func_parameters[ $name ] = $function->getParameters();
        }

        return self::$func_parameters[ $name ];
    }
}
