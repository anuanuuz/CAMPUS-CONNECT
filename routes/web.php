<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    AuthController,
    Admin\AdminDashboardController,
    Admin\AdminUsersController,
    Admin\AdminEventsController,
    Alumni\AlumniController,
    Alumni\ProfileController,
    Alumni\DirectoryController,
    Events\EventController,
    Events\EventRsvpController,
    Messages\MessageController,
    News\NewsController,
    Gallery\GalleryController,
    Careers\CareerController,
    NotificationController,
    SearchController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/donate', [HomeController::class, 'donate'])->name('donate');

// Global Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Social Authentication
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.social');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.social.callback');

// Email Verification
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])->name('verification.send');
});

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard/Profile Routes
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload.avatar');
    Route::post('/profile/upload-cover', [ProfileController::class, 'uploadCover'])->name('profile.upload.cover');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Alumni Directory Routes (Public + Enhanced for Members)
Route::prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/', [DirectoryController::class, 'index'])->name('directory');
    Route::get('/search', [DirectoryController::class, 'search'])->name('search');
    Route::get('/filter', [DirectoryController::class, 'filter'])->name('filter');
    Route::get('/{user}', [DirectoryController::class, 'show'])->name('show');
    
    // Authenticated Alumni Features
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/{user}/connect', [DirectoryController::class, 'sendConnectionRequest'])->name('connect');
        Route::post('/connections/{connection}/respond', [DirectoryController::class, 'respondToConnection'])->name('connection.respond');
        Route::get('/my/connections', [DirectoryController::class, 'myConnections'])->name('connections');
        Route::get('/my/requests', [DirectoryController::class, 'connectionRequests'])->name('connection-requests');
    });
});

// Events Routes
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/search', [EventController::class, 'search'])->name('search');
    Route::get('/calendar', [EventController::class, 'calendar'])->name('calendar');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    
    // Authenticated Event Features
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/{event}/rsvp', [EventRsvpController::class, 'store'])->name('rsvp');
        Route::delete('/{event}/rsvp', [EventRsvpController::class, 'destroy'])->name('rsvp.cancel');
        Route::get('/my/events', [EventController::class, 'myEvents'])->name('my-events');
        Route::get('/my/rsvps', [EventController::class, 'myRsvps'])->name('my-rsvps');
        
        // Event Creation (Alumni can suggest events)
        Route::get('/create/suggest', [EventController::class, 'suggest'])->name('suggest');
        Route::post('/create/suggest', [EventController::class, 'submitSuggestion'])->name('suggest.submit');
    });
});

// News Routes
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/search', [NewsController::class, 'search'])->name('search');
    Route::get('/category/{category}', [NewsController::class, 'category'])->name('category');
    Route::get('/{article}', [NewsController::class, 'show'])->name('show');
});

// Gallery Routes
Route::prefix('gallery')->name('gallery.')->group(function () {
    Route::get('/', [GalleryController::class, 'index'])->name('index');
    Route::get('/events/{event}', [GalleryController::class, 'eventGallery'])->name('event');
    Route::get('/{item}', [GalleryController::class, 'show'])->name('show');
    
    // Authenticated Gallery Features
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/upload', [GalleryController::class, 'upload'])->name('upload');
        Route::delete('/{item}', [GalleryController::class, 'destroy'])->name('destroy');
    });
});

// Messages Routes (Authenticated Only)
Route::middleware(['auth', 'verified'])->prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [MessageController::class, 'index'])->name('index');
    Route::get('/compose', [MessageController::class, 'compose'])->name('compose');
    Route::post('/send', [MessageController::class, 'send'])->name('send');
    Route::get('/conversation/{user}', [MessageController::class, 'conversation'])->name('conversation');
    Route::get('/{message}', [MessageController::class, 'show'])->name('show');
    Route::post('/{message}/reply', [MessageController::class, 'reply'])->name('reply');
    Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
    Route::post('/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('mark-read');
});

// Career Center Routes
Route::prefix('careers')->name('careers.')->group(function () {
    Route::get('/jobs', [CareerController::class, 'jobs'])->name('jobs');
    Route::get('/jobs/{job}', [CareerController::class, 'showJob'])->name('jobs.show');
    Route::get('/mentorship', [CareerController::class, 'mentorship'])->name('mentorship');
    Route::get('/resources', [CareerController::class, 'resources'])->name('resources');
    
    // Authenticated Career Features
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/my/applications', [CareerController::class, 'myApplications'])->name('applications');
        Route::post('/jobs/{job}/apply', [CareerController::class, 'applyToJob'])->name('jobs.apply');
        Route::get('/post-job', [CareerController::class, 'createJob'])->name('jobs.create');
        Route::post('/post-job', [CareerController::class, 'storeJob'])->name('jobs.store');
        Route::get('/mentorship/request/{user}', [CareerController::class, 'requestMentorship'])->name('mentorship.request');
    });
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/system-info', [AdminDashboardController::class, 'systemInfo'])->name('system-info');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('index');
        Route::get('/create', [AdminUsersController::class, 'create'])->name('create');
        Route::post('/', [AdminUsersController::class, 'store'])->name('store');
        Route::get('/{user}', [AdminUsersController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/activate', [AdminUsersController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [AdminUsersController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/feature', [AdminUsersController::class, 'feature'])->name('feature');
        Route::post('/{user}/verify-email', [AdminUsersController::class, 'verifyEmail'])->name('verify-email');
        Route::get('/export/csv', [AdminUsersController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/excel', [AdminUsersController::class, 'exportExcel'])->name('export.excel');
    });
    
    // Event Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventsController::class, 'index'])->name('index');
        Route::get('/create', [AdminEventsController::class, 'create'])->name('create');
        Route::post('/', [AdminEventsController::class, 'store'])->name('store');
        Route::get('/{event}', [AdminEventsController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [AdminEventsController::class, 'edit'])->name('edit');
        Route::put('/{event}', [AdminEventsController::class, 'update'])->name('update');
        Route::delete('/{event}', [AdminEventsController::class, 'destroy'])->name('destroy');
        Route::get('/{event}/attendees', [AdminEventsController::class, 'attendees'])->name('attendees');
        Route::get('/{event}/export-attendees', [AdminEventsController::class, 'exportAttendees'])->name('export-attendees');
        Route::post('/{event}/send-reminder', [AdminEventsController::class, 'sendReminder'])->name('send-reminder');
        Route::get('/suggestions/pending', [AdminEventsController::class, 'pendingSuggestions'])->name('suggestions');
        Route::post('/suggestions/{suggestion}/approve', [AdminEventsController::class, 'approveSuggestion'])->name('suggestions.approve');
        Route::post('/suggestions/{suggestion}/reject', [AdminEventsController::class, 'rejectSuggestion'])->name('suggestions.reject');
    });
    
    // Content Management
    Route::prefix('content')->name('content.')->group(function () {
        // News Management
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [AdminNewsController::class, 'index'])->name('index');
            Route::get('/create', [AdminNewsController::class, 'create'])->name('create');
            Route::post('/', [AdminNewsController::class, 'store'])->name('store');
            Route::get('/{article}/edit', [AdminNewsController::class, 'edit'])->name('edit');
            Route::put('/{article}', [AdminNewsController::class, 'update'])->name('update');
            Route::delete('/{article}', [AdminNewsController::class, 'destroy'])->name('destroy');
            Route::post('/{article}/publish', [AdminNewsController::class, 'publish'])->name('publish');
            Route::post('/{article}/unpublish', [AdminNewsController::class, 'unpublish'])->name('unpublish');
        });
        
        // Gallery Management
        Route::prefix('gallery')->name('gallery.')->group(function () {
            Route::get('/', [AdminGalleryController::class, 'index'])->name('index');
            Route::post('/upload', [AdminGalleryController::class, 'upload'])->name('upload');
            Route::delete('/{item}', [AdminGalleryController::class, 'destroy'])->name('destroy');
            Route::post('/{item}/approve', [AdminGalleryController::class, 'approve'])->name('approve');
            Route::post('/{item}/feature', [AdminGalleryController::class, 'feature'])->name('feature');
        });
    });
    
    // Settings & Configuration
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index'])->name('index');
        Route::put('/general', [AdminSettingsController::class, 'updateGeneral'])->name('general');
        Route::put('/email', [AdminSettingsController::class, 'updateEmail'])->name('email');
        Route::put('/social', [AdminSettingsController::class, 'updateSocial'])->name('social');
        Route::get('/backup', [AdminSettingsController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [AdminSettingsController::class, 'createBackup'])->name('backup.create');
        Route::get('/logs', [AdminSettingsController::class, 'logs'])->name('logs');
        Route::post('/logs/clear', [AdminSettingsController::class, 'clearLogs'])->name('logs.clear');
    });
    
    // Communications
    Route::prefix('communications')->name('communications.')->group(function () {
        Route::get('/broadcasts', [AdminCommunicationsController::class, 'broadcasts'])->name('broadcasts');
        Route::get('/broadcasts/create', [AdminCommunicationsController::class, 'createBroadcast'])->name('broadcasts.create');
        Route::post('/broadcasts', [AdminCommunicationsController::class, 'sendBroadcast'])->name('broadcasts.send');
        Route::get('/templates', [AdminCommunicationsController::class, 'templates'])->name('templates');
        Route::get('/analytics', [AdminCommunicationsController::class, 'analytics'])->name('analytics');
    });
});

// API Routes for AJAX calls
Route::prefix('api')->name('api.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::get('/messages/count', [MessageController::class, 'unreadCount'])->name('messages.count');
    Route::get('/events/upcoming', [EventController::class, 'upcomingApi'])->name('events.upcoming');
    Route::get('/alumni/suggestions', [DirectoryController::class, 'suggestions'])->name('alumni.suggestions');
    Route::get('/search/quick', [SearchController::class, 'quick'])->name('search.quick');
});

// API Documentation (for developers)
Route::get('/api/docs', function () {
    return view('api.documentation');
})->name('api.docs');

// Webhooks
Route::post('/webhooks/email', [WebhookController::class, 'email'])->name('webhooks.email');
Route::post('/webhooks/payment', [WebhookController::class, 'payment'])->name('webhooks.payment');

// Fallback route for 404 pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});