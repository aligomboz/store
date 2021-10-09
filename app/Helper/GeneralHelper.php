<?php

use Illuminate\Support\Facades\Cache;

function getParentShowOf($parem)
{
    $route = str_replace('admin.', '', $parem);
    $permissinon = Cache::get('admin_side_menu')->where('as', $route)->first();
    return $permissinon ? $permissinon->parent_show : $route;
}


function getParentOf($parem)
{
    $route = str_replace('admin.', '', $parem);
    $permissinon =  Cache::get('admin_side_menu')->where('as', $route)->first();
    return $permissinon ? $permissinon->parent : $route;
}

function getParentIdOf($parem)
{
    $route = str_replace('admin.', '', $parem);
    $permissinon =  Cache::get('admin_side_menu')->where('as', $route)->first();
    return $permissinon ? $permissinon->id : null;
}