<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

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
        // 1. Log SQL Query để debug (Chỉ chạy khi bật debug trong .env)
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::info('SQL Query Executed', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . ' ms',
                ]);
            });
        }

        // 2. Cấu hình Phân trang dùng Bootstrap
        Paginator::useBootstrap();

        // 3. Cấu hình Mail xác thực
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Xác thực địa chỉ Email')
                ->line('Vui lòng nhấn vào nút bên dưới để xác thực email của bạn.')
                ->action('Xác thực ngay', $url);
        });

        // 4. Cấu hình Ngôn ngữ thời gian
        Carbon::setLocale('vi');

        /**
         * 5. Chia sẻ biến OrderCount cho TẤT CẢ các View
         * Dùng View::share kết hợp Cache để chống lặp query (gây lỗi 502)
         * Bọc Try-Catch để web không bị sập nếu Database có vấn đề
         */
        try {
            $OrderCount = Cache::remember('admin_order_count_pending', 600, function () {
                return Order::where('status', 1)->where('thanh_toan', 1)->count();
            });
            
            View::share('OrderCount', $OrderCount);
            
        } catch (\Exception $e) {
            // Ghi lại lỗi chi tiết vào file storage/logs/laravel.log
            Log::error('Lỗi khi đếm OrderCount ở AppServiceProvider: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Gán tạm = 0 để giao diện web vẫn load bình thường
            View::share('OrderCount', 0);
        }
    }
}