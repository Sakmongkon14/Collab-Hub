<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <- ต้องใช้แบบนี้
use Illuminate\Support\Facades\DB;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // ทำให้ layouts.Tailwind ทุกหน้า มีตัวแปร $pendingJobs และ $countPending
    View::composer('layouts.Tailwind', function ($view) {
        $pendingJobs = DB::table('collab_newjob')
            ->where('Job_Adding_Status', 'Pending')
            ->get();
            //dd($pendingJobs);

        $view->with([
            'pendingJobs' => $pendingJobs,
            'countPending' => $pendingJobs->count(),
        ]);
    });
    }
}
