<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
            $user = Auth::user();
            $headerUserName = $user
                ? ($user->display_name ?: Str::before($user->email, '@'))
                : 'ゲスト';
            $headerGroupName = 'マイホーム';

            try {
                if (Schema::hasTable('items') && Schema::hasTable('groups')) {
                    $group = $user ? ($user->primaryGroup() ?? Group::first()) : null;
                    if ($group) {
                        $headerGroupName = $group->name;

                        // 在庫が少なくなった商品
                        $lowStockItems = Item::where('group_id', $group->id)
                            ->whereNull('archived_at')
                            ->where(function ($q) {
                                $q->whereColumn('stock_quantity', '<=', 'reorder_level')
                                  ->orWhere('stock_quantity', '<=', 1);
                            })
                            ->take(5)
                            ->get();

                        // 購入周期が近づいたもの（昇順）
                        $cycleItems = Item::where('group_id', $group->id)
                            ->whereNull('archived_at')
                            ->where('is_recurring', true)
                            ->whereNotNull('purchase_cycle_days')
                            ->get()
                            ->sortBy(function ($item) {
                                return $item->days_until_next_purchase ?? 999;
                            })
                            ->take(5)
                            ->values();

                        $totalCount = $lowStockItems->count() + $cycleItems->where('days_until_next_purchase', '<=', 7)->count();

                        $view->with('headerLowStockItems', $lowStockItems);
                        $view->with('headerCycleItems', $cycleItems);
                        $view->with('headerNotificationCount', $totalCount);
                        $view->with('headerGroupName', $headerGroupName);
                        $view->with('headerUserName', $headerUserName);
                        return;
                    }
                }
            } catch (\Exception $e) {
                // Ignore during migrations or initial setup
            }

            $view->with('headerLowStockItems', collect([]));
            $view->with('headerCycleItems', collect([]));
            $view->with('headerNotificationCount', 0);
            $view->with('headerGroupName', $headerGroupName);
            $view->with('headerUserName', $headerUserName);
        });
    }
}
