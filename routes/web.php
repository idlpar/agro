    <?php

    use App\Http\Controllers\CustomerController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\ProductVariantController;
    use App\Http\Controllers\CustomerPriceController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\ReportController;
    use App\Http\Controllers\TransactionController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\VisitController;
    use App\Models\User;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User management routes
        Route::resource('users', UserController::class)->except(['create', 'store']);
        Route::get('users/create/{role?}', [UserController::class, 'create'])->name('users.create');
        Route::post('users/store', [UserController::class, 'store'])->name('users.store');
        Route::post('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        Route::get('/staff', [UserController::class, 'staffIndex'])->name('staff.index');

        Route::resource('customers', CustomerController::class)->parameters([
            'customers' => 'customer'
        ]);
        Route::get('/customers/filter/{filter}', [CustomerController::class, 'index'])->name('customers.filter');
        Route::post('customers/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');

        // Customer specific prices
        Route::prefix('customers/{customer}')->group(function () {
            Route::resource('prices', CustomerPriceController::class)->except(['show']);
        });

        // Product management routes
        Route::get('products/{product}/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');

        Route::prefix('products/{product}')->group(function () {
            Route::resource('variants', ProductVariantController::class)
                ->except(['index', 'show'])
                ->parameters([
                    'variants' => 'variant',
                ]);
        });
        Route::resource('products', ProductController::class);
        
       


        // Transaction routes
        Route::resource('transactions', TransactionController::class);
        Route::get('transactions/report', [TransactionController::class, 'report'])->name('transactions.report');
        Route::get('/transactions/filter/{status}', [TransactionController::class, 'index'])->name('transactions.filter');

        // Payments routes
        // Custom GET routes - place BEFORE resource() to avoid conflicts
        Route::get('payments/due-list', [PaymentController::class, 'dueList'])->name('payments.dueList');
        Route::get('payments/visit-list', [PaymentController::class, 'visitList'])->name('payments.visitList');
        Route::get('payments/customer/{customerId}', [PaymentController::class, 'customerPayments'])->name('payments.customerPayments');
        Route::get('payments/customer/{customer}/transactions', [PaymentController::class, 'customerTransactions'])->name('payments.customerTransactions');
//        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

         // Resource routes - placed last to avoid route conflicts
        Route::resource('payments', PaymentController::class);

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('visits/collection-list', [VisitController::class, 'collectionList'])->name('visits.collection-list');
        Route::post('visits/{visit}/complete', [VisitController::class, 'markComplete'])->name('visits.complete');
        Route::resource('visits', VisitController::class);

        // Reports route
        Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
                
        Route::get('/force-clear', function () {
            try {
                Artisan::call('route:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('cache:clear');
                Artisan::call('clear-compiled');
                Artisan::call('optimize:clear');
        
                return '✅ All Laravel caches cleared!';
            } catch (\Exception $e) {
                return '❌ Error: ' . $e->getMessage();
            }
        });

    });


    require __DIR__.'/auth.php';

