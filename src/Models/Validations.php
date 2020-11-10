<?php

namespace SRC\Models;

class Validations
{
    public static function validationHash($hash = null)
    {
        $dir = __DIR__.'/../../config/config.ini';
        $conf = parse_ini_file($dir, true);

        $cnpj = false;
        $i=0;
        while (!$cnpj && $i < count($conf['DEFAULT'])) {
            $cnpj = ($hash == md5($conf['DEFAULT'][$i].$conf['HASH']['keyword']) 
            ? $conf['DEFAULT'][$i] : false );
            if ($cnpj != false) break;
            $i++;
        }
        return $cnpj;
    }

    public static function removeSpaces($value = null)
    {
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);
        return $value = rtrim($value);
    }

    public static function validationEstock($value = null)
    {
        $value = intval($value);
        if ($value < 0) {
            return 0;
        } else {
           return $value; 
        }
    }
}
