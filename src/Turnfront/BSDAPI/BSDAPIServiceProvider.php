<?php namespace Turnfront\BSDAPI;

use Illuminate\Support\ServiceProvider;

class BSDAPIServiceProvider extends ServiceProvider {

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
	public function register()
	{

	}

  public function boot(){
    $this->package("turnfront\\bsdapi", "bsdapi", dirname(__FILE__));
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