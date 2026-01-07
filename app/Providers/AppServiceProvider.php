<?php
namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

// ⭐ อันนี้แหละที่ขาด!

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
        View::composer('*', function ($view) {

            if (! Auth::check()) {
                return;
            }

            $user = Auth::user();

            // ================= ADMIN =================
            if ($user->status === 'Admin') {

                // 🔵 งานที่ต้องพิจารณา
                $unreadNotifications = DB::table('collab_newjob')
                    ->where('is_read', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();

                // 🟢 ประวัติที่จัดการแล้ว
                $readNotifications = DB::table('collab_newjob')
                    ->where('is_read', 1)
                    ->orderBy('updated_at', 'desc')
                    ->limit(20)
                    ->get();
            }

            // ================= USER =================
            else {

                // 🔔 ผลการอนุมัติใหม่
                $unreadNotifications = DB::table('collab_newjob')
                    ->where('Requester', $user->name)
                    ->where('is_read', 0)
                    ->whereIn('Job_Adding_Status', ['Approved', 'Rejected'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

                // 🟢 ผลการอนุมัติที่ผ่านมา
                $readNotifications = DB::table('collab_newjob')
                    ->where('Requester', $user->name)
                    ->where('is_read', 1)
                    ->orderBy('updated_at', 'desc')
                    ->limit(20)
                    ->get();
            }

            $view->with(compact('unreadNotifications', 'readNotifications'));
        });

        View::composer('layouts.user', function ($view) {

                                             // ตรวจสอบ Project 16 ว่ามี member_status = 'yes' หรือไม่
            $userId            = Auth::id(); // หรือ user ที่ต้องการเช็ค
            $showProjectView16 = DB::table('collab_user_permissions')
                ->where('project_code', 'like', '16%')
                ->where('user_id', $userId)
                ->where('member_status', 'yes')
                ->exists();
            //dd($showProjectView16);

            $view->with([
                'showProjectView16' => $showProjectView16,
            ]);
        });

    }

}
