<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ConsultationLeadController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectProgressController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/architecture', [ServiceController::class, 'architecture'])->name('services.architecture');
Route::get('/services/structural', [ServiceController::class, 'structural'])->name('services.structural');
Route::get('/services/geotechnical', [ServiceController::class, 'geotechnical'])->name('services.geotechnical');
Route::get('/services/construction', [ServiceController::class, 'construction'])->name('services.construction');
Route::get('/services/interior', [ServiceController::class, 'interior'])->name('services.interior');
Route::get('/services/mep', [ServiceController::class, 'mep'])->name('services.mep');
Route::get('/services/approval', [ServiceController::class, 'approval'])->name('services.approval');
Route::get('/services/documentation', [ServiceController::class, 'documentation'])->name('services.documentation');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/team/{member:slug}', [TeamController::class, 'show'])->name('team.show');

Route::get('/insights', [BlogController::class, 'index'])->name('blog');
Route::get('/insights/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/start-your-project', [ConsultationController::class, 'create'])->name('consultation');
Route::post('/start-your-project', [ConsultationController::class, 'store'])->name('consultation.store');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/international-sop', [PageController::class, 'internationalSop'])->name('international-sop');

Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'role:super-admin|admin|editor|project-manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class);
    Route::delete('projects/{project}/images/{image}', [App\Http\Controllers\Admin\ProjectController::class, 'destroyImage'])
        ->name('projects.images.destroy');
    Route::resource('projects.progress', ProjectProgressController::class)->shallow();
    Route::resource('project-categories', ProjectCategoryController::class)
        ->parameters(['project-categories' => 'category']);

    Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('service-categories', ServiceCategoryController::class)
        ->parameters(['service-categories' => 'category']);

    Route::resource('hero-slides', HeroSlideController::class)
        ->except(['show'])
        ->parameters(['hero-slides' => 'heroSlide']);
    Route::patch('hero-slides/{heroSlide}/toggle', [HeroSlideController::class, 'toggle'])
        ->name('hero-slides.toggle');

    Route::resource('team', TeamMemberController::class)
        ->parameters(['team' => 'member']);
    Route::resource('offices', OfficeController::class);

    Route::resource('blog', BlogPostController::class)
        ->parameters(['blog' => 'post']);
    Route::resource('blog-categories', BlogCategoryController::class)
        ->parameters(['blog-categories' => 'category']);
    Route::resource('tags', TagController::class);

    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::resource('menus', MenuController::class);
    Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
    Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
    Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');

    Route::resource('consultations', ConsultationLeadController::class)
        ->parameters(['consultations' => 'lead']);
    Route::resource('contacts', ContactMessageController::class)
        ->parameters(['contacts' => 'contact_message']);

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('seo', [App\Http\Controllers\Admin\SeoController::class, 'index'])->name('seo.index');
    Route::put('seo', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('seo.update');

    Route::middleware('role:super-admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    Route::get('activity', [ActivityController::class, 'index'])->name('activity.index');
});

Route::get('sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');
