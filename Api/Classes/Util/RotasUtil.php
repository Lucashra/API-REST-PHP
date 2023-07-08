<?php

namespace Util;

use Util\FuncoesUtil as  Util;
class RotasUtil
{
    public static function getRotas()
    {
       $urls = self::getUrls();

       $request = [];
       $request['rota'] = strtoupper($urls[2]);
       $request['recurso'] = $urls[3] ?? null;
       $request['id'] = filter_var($urls[4],);
       $request['metodo'] = $_SERVER['REQUEST_METHOD'];
       return $request;
    }
    
    public static function getUrls()
    {
        $uri = str_replace('/'. DIR_PROJETO, '', $_SERVER['REQUEST_URI']);
        return explode('/', trim($uri, '/'));
    }
}