<?php
namespace Chencha\Everlytic\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * The everlytic facade
 *
 * @author jacob
 */
class Everlytic  extends Facade{
     protected static function getFacadeAccessor() { return 'everlytic'; }
}
