<?php
namespace CodeOrange\Statuspage;

use Illuminate\Support\ServiceProvider;

class StatuspageProvider extends ServiceProvider {
	public function register() {
		$this->app->singleton(Statuspage::class);
	}

	public function boot() {
		$this->loadViewsFrom(__DIR__ . '/views', 'statuspage');

		$this->app->router->get(env('STATUSPAGE_ROUTE', '/'), Controller::class . '@status');

		if (substr(env('STATUSPAGE_ROUTE', '/'), -1) === '/') {
			$this->app->router->get(env('STATUSPAGE_ROUTE', '/') . 'json', Controller::class . '@json');
		} else {
			$this->app->router->get(env('STATUSPAGE_ROUTE', '/') . '.json', Controller::class . '@json');
		}
	}
}
