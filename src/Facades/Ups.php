<?php

namespace KS\Ups\Facades;

use Illuminate\Support\Facades\Facade;


class Ups extends Facade
{

  protected static function getFacadeAccessor() { return 'ups'; }
}
