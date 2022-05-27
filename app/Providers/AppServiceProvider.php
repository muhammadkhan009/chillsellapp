<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Providers;

use App\Helpers\Date;
use App\Models\Sanctum\PersonalAccessToken;
use App\Providers\AppService\AclSystemTrait;
use App\Providers\AppService\ConfigTrait;
use App\Providers\AppService\SymlinkTrait;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\Web\Search\Traits\CategoryTrait;

class AppServiceProvider extends ServiceProvider
{
	use AclSystemTrait, ConfigTrait, SymlinkTrait, CategoryTrait;
	
	private $cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
	
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Paginator::useBootstrap();
		
		try {
			// Specified key was too long error
			Schema::defaultStringLength(191);
		} catch (\Exception $e) {
		}
		
		try {
			// Setup Laravel Sanctum
			Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
		} catch (\Exception $e) {
		}
		
		// Setup Storage Symlink
		$this->setupStorageSymlink();
		
		// Setup ACL system
		$this->setupAclSystem();
		
		// Setup Https
		$this->setupHttps();
		
		// Setup Configs
		$this->setupConfigs();
		
		// Date default encoding & translation
		// The translation option is overwritten when applying the front-end settings
		if (config('settings.app.date_force_utf8')) {
			Carbon::setUtf8(true);
		}
		Date::setAppLocale(config('appLang.locale', 'en_US'));

		//custom include
		$rootCats = $this->getRootCategories();
		view()->share('rootCats', $rootCats);
		
		// Get Category
		$this->cat = $this->getCategory();
		
		// Get Category's Subcategories
		$popCatId = (isset($cat->parent, $cat->parent->parent) && !empty($cat->parent->parent))
			? $cat->parent->parent->id
			: ((isset($cat->parent) && !empty($cat->parent)) ? $cat->parent->id : null);
		$cats = $this->getCategories($popCatId);
		view()->share('cats', $cats); 
	}
	
	/**
	 * Setup Https
	 */
	private function setupHttps()
	{
		// Force HTTPS protocol
		if (config('larapen.core.forceHttps') == true) {
			URL::forceScheme('https');
		}
	}
}
