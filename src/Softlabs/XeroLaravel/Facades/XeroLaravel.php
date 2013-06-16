<?php namespace Softlabs\XeroLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class XeroLaravel extends Facade
{
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'xero'; }
}