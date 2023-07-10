<?php

namespace Util;

abstract class FuncoesUtil
{
    public static function print_rpre($dado = null)
    {
        echo "<pre>";
        print_r($dado);
        echo"</pre>";
    }
}