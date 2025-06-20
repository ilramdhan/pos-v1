<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\Receipt\ReceiptController;
use App\Livewire\Branch\LocationManagement;
use App\Livewire\Cashier\Cashier;
use App\Livewire\Cashier\CashierSessionManagement;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Inventory\StockOpname;
use App\Livewire\Inventory\StockTransferCreate;
use App\Livewire\Inventory\StockTransferDetail;
use App\Livewire\Inventory\StockTransferList;
use App\Livewire\Management\CustomerDetail;
use App\Livewire\Management\CustomerManagement;
use App\Livewire\Management\RolePermissionManagement;
use App\Livewire\Management\UserManagement;
use App\Livewire\Product\AttributeManagement;
use App\Livewire\Product\BrandProduct;
use App\Livewire\Product\CategoryProduct;
use App\Livewire\Product\ListProduct;
use App\Livewire\Product\ProductForm;
use App\Livewire\Purchase\PurchaseOrderCreate;
use App\Livewire\Purchase\PurchaseOrderDetail;
use App\Livewire\Purchase\PurchaseOrderList;
use App\Livewire\Report\ProfitabilityByBrandReport;
use App\Livewire\Report\ProfitLossReport;
use App\Livewire\Report\SalesByCategoryReport;
use App\Livewire\Report\SalesByCustomerReport;
use App\Livewire\Report\StockAdjustmentReport;
use App\Livewire\Report\StockCardReport;
use App\Livewire\Sale\SaleDetail;
use App\Livewire\Sale\SaleList;
use App\Livewire\Settings\ApplicationSettings;
use App\Livewire\Supplier\ManagementSupplier;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:Super Admin'
])->group(function () {
    Route::get('/user-management', UserManagement::class)->name('user.management');
    Route::get('/role-permission-management', RolePermissionManagement::class)->name('role.permission.management');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/categories', CategoryProduct::class)->name('categories.product')->middleware('permission:manage-categories');
    Route::get('/brands', BrandProduct::class)->name('brands.product')->middleware('permission:manage-brands');
    Route::get('/products', ListProduct::class)->name('products.index')->middleware('permission:manage-product-variants');
    Route::get('/products/create', ProductForm::class)->name('products.create')->middleware('permission:create-products');
    Route::get('/products/{productId}/edit', ProductForm::class)->name('products.edit')->middleware('permission:edit-products');

    Route::get('/product-attributes', AttributeManagement::class)->name('attributes.index')->middleware('permission:manage-products');

    Route::get('/suppliers', ManagementSupplier::class)->name('management.suppliers')->middleware('permission:manage-suppliers');
    Route::get('/purchases', PurchaseOrderList::class)->name('purchases.orders')->middleware('permission:create-purchase-orders');
    Route::get('/purchases/create', PurchaseOrderCreate::class)->name('purchases.create')->middleware('permission:create-purchase-orders');
    Route::get('/purchases/{purchaseOrder}', PurchaseOrderDetail::class)->name('purchases.show')->middleware('permission:create-purchase-orders');
    Route::get('/reports/stock-card', StockCardReport::class)->name('reports.stock-card')->middleware('permission:view-stock-cards');

    Route::get('/cashier', Cashier::class)->name('cashier.index')->middleware('permission:access-cashier');
    Route::get('/sales', SaleList::class)->name('sales.index')->middleware('permission:view-sales-reports');
    Route::get('/sales/{sale}', SaleDetail::class)->name('sales.show')->middleware('permission:view-sales-reports');

    Route::get('/sales/{sale}/print', [ReceiptController::class, 'showSaleReceipt'])->name('sales.print')->middleware('permission:view-sales-reports');
    Route::get('/customers', CustomerManagement::class)->name('customers.index')->middleware('permission:manage-customers');
    Route::get('/customers/{customer}', CustomerDetail::class)->name('customers.show')->middleware('permission:manage-customers');

    Route::get('/dashboard', Dashboard::class)->name('dashboard')->middleware(['auth', 'verified']);
    Route::get('/reports/profit-loss', ProfitLossReport::class)->name('reports.profit-loss')->middleware('permission:view-profit-loss-reports');
    Route::get('/sessions', CashierSessionManagement::class)->name('sessions.index')->middleware('permission:manage-cashier-sessions');

    Route::get('/inventory/stock-opname', StockOpname::class)->name('inventory.stock-opname')->middleware('permission:manage-stock-opname');
    Route::get('/reports/stock-adjustments', StockAdjustmentReport::class)->name('reports.stock-adjustments')->middleware('permission:manage-stock-opname');
    Route::get('/reports/sales-by-category', SalesByCategoryReport::class)->name('reports.sales-by-category')->middleware('permission:view-sales-reports');
    Route::get('/reports/sales-by-customer', SalesByCustomerReport::class)->name('reports.sales-by-customer')->middleware('permission:view-sales-reports');
    Route::get('/reports/profitability-by-brand', ProfitabilityByBrandReport::class)->name('reports.profitability-by-brand')->middleware('permission:view-profit-loss-reports');

    Route::get('/settings/application', ApplicationSettings::class)->name('settings.application')->middleware('permission:manage-settings');

    Route::get('/inventory/locations', LocationManagement::class)->name('inventory.locations')->middleware('permission:manage-transfer-stock');
    Route::get('/inventory/transfers', StockTransferList::class)->name('inventory.transfers.index')->middleware('permission:manage-transfer-stock');
    Route::get('/inventory/create', StockTransferCreate::class)->name('inventory.transfers.create')->middleware('permission:manage-transfer-stock');
    Route::get('/inventory/detail/{transfer}', StockTransferDetail::class)->name('inventory.transfers.detail')->middleware('permission:manage-transfer-stock');

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/utility/404', function () {
        return view('pages/utility/404');
    })->name('404');
    Route::get('/component/button', function () {
        return view('pages/component/button-page');
    })->name('button-page');
    Route::get('/component/form', function () {
        return view('pages/component/form-page');
    })->name('form-page');
    Route::get('/component/dropdown', function () {
        return view('pages/component/dropdown-page');
    })->name('dropdown-page');
    Route::get('/component/alert', function () {
        return view('pages/component/alert-page');
    })->name('alert-page');
    Route::get('/component/modal', function () {
        return view('pages/component/modal-page');
    })->name('modal-page');
    Route::get('/component/pagination', function () {
        return view('pages/component/pagination-page');
    })->name('pagination-page');
    Route::get('/component/tabs', function () {
        return view('pages/component/tabs-page');
    })->name('tabs-page');
    Route::get('/component/breadcrumb', function () {
        return view('pages/component/breadcrumb-page');
    })->name('breadcrumb-page');
    Route::get('/component/badge', function () {
        return view('pages/component/badge-page');
    })->name('badge-page');
    Route::get('/component/avatar', function () {
        return view('pages/component/avatar-page');
    })->name('avatar-page');
    Route::get('/component/tooltip', function () {
        return view('pages/component/tooltip-page');
    })->name('tooltip-page');
    Route::get('/component/accordion', function () {
        return view('pages/component/accordion-page');
    })->name('accordion-page');
    Route::get('/component/icons', function () {
        return view('pages/component/icons-page');
    })->name('icons-page');
    Route::fallback(function() {
        return view('pages/utility/404');
    });
});
