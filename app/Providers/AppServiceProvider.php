<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Item;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            try {
                if (Schema::hasTable('items') && Schema::hasTable('groups')) {
                    $group = Group::first();
                    if ($group) {
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
                        return;
                    }
                }
            } catch (\Exception $e) {
                // Ignore during migrations or initial setup
            }

            $view->with('headerLowStockItems', collect([]));
            $view->with('headerCycleItems', collect([]));
            $view->with('headerNotificationCount', 3);
        });
    }
}
