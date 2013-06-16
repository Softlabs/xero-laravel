<?php namespace Softlabs\XeroLaravel;

use Illuminate\Support\ServiceProvider;
use Xero;

class XeroLaravelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register($config, $format = 'json')
    {
        return new Xero($config->consumer_key(), $config->consumer_secret(), $config->cert_path_public(),
            $config->cert_path_private(), $format);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}