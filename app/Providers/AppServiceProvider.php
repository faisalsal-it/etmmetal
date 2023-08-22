<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\BasicSetting as BS;
use App\BasicExtended as BE;
use App\Social;
use App\Ulink;
use App\Page;
use App\Scategory;
use App\Language;

class AppServiceProvider extends ServiceProvider
{

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
		
        $socials = Social::orderBy('serial_number', 'ASC')->get();
        $langs = Language::all();

        view()->composer('*', function ($view)
        {
            if (session()->has('lang')) {
                $currentLang = Language::where('code', session()->get('lang'))->first();
            } else {
                $currentLang = Language::where('is_default', 1)->first();
            }

            $bs = $currentLang->basic_setting;
            $be = $currentLang->basic_extended;
            $ulinks = $currentLang->ulinks;
            $scats = $currentLang->scategories()->orderBy('serial_number', 'ASC')->get();
            $pages = $currentLang->pages()->where('status', 1)->orderBy('serial_number', 'ASC')->get();
            if ($currentLang->rtl == 1) {
                $rtl = 1;
            } else {
                $rtl = 0;
            }

            $view->with('bs', $bs );
            $view->with('be', $be );
            $view->with('scats', $scats );
            $view->with('ulinks', $ulinks );
            $view->with('pages', $pages );
            $view->with('currentLang', $currentLang );
            $view->with('rtl', $rtl );
        });

        View::share('socials', $socials);
        View::share('langs', $langs);
    }
}
