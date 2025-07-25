<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'username',
        'password',
        'phone',
        'date_of_birth',
        'graduation_year',
        'department',
        'degree',
        'student_id',
        'bio',
        'profile_picture',
        'cover_picture',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'company_name',
        'job_title',
        'industry',
        'website',
        'linkedin_url',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'github_url',
        'is_active',
        'is_featured',
        'email_verified_at',
        'last_login_at',
        'login_count',
        'profile_completion',
        'privacy_settings',
        'notification_preferences',
        'timezone',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'login_count' => 'integer',
        'profile_completion' => 'integer',
        'privacy_settings' => 'array',
        'notification_preferences' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'is_active' => true,
        'is_featured' => false,
        'login_count' => 0,
        'profile_completion' => 0,
        'country' => 'United States',
        'timezone' => 'America/New_York',
        'language' => 'en',
        'privacy_settings' => '{"profile_visibility": "public", "email_visibility": "alumni", "phone_visibility": "private"}',
        'notification_preferences' => '{"email_events": true, "email_messages": true, "email_news": true, "push_notifications": true}',
    ];

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'email', 'profile_picture', 'job_title', 'company_name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    /**
     * Get the user's initials.
     */
    public function getInitialsAttribute(): string
    {
        $names = collect([$this->first_name, $this->last_name])->filter();
        return $names->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('');
    }

    /**
     * Get the user's profile picture URL.
     */
    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        
        // Generate avatar based on initials
        return "https://ui-avatars.com/api/?name=" . urlencode($this->full_name) . 
               "&size=200&background=667eea&color=ffffff&font-size=0.6";
    }

    /**
     * Get the user's cover picture URL.
     */
    public function getCoverPictureUrlAttribute(): string
    {
        if ($this->cover_picture) {
            return asset('storage/' . $this->cover_picture);
        }
        
        return asset('images/default-cover.jpg');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is alumni.
     */
    public function isAlumni(): bool
    {
        return $this->hasRole('alumni');
    }

    /**
     * Get profile completion percentage.
     */
    public function calculateProfileCompletion(): int
    {
        $fields = [
            'first_name', 'last_name', 'email', 'phone', 'date_of_birth',
            'graduation_year', 'department', 'bio', 'profile_picture',
            'city', 'country', 'company_name', 'job_title'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Update profile completion.
     */
    public function updateProfileCompletion(): void
    {
        $this->update(['profile_completion' => $this->calculateProfileCompletion()]);
    }

    /**
     * Events created by this user.
     */
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Events the user has RSVP'd to.
     */
    public function rsvpEvents()
    {
        return $this->belongsToMany(Event::class, 'event_rsvps')
                    ->withPivot(['status', 'response_date', 'comments'])
                    ->withTimestamps();
    }

    /**
     * Messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * News articles authored by this user.
     */
    public function newsArticles()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Gallery items uploaded by this user.
     */
    public function galleryItems()
    {
        return $this->hasMany(Gallery::class, 'uploaded_by');
    }

    /**
     * Job postings created by this user.
     */
    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'posted_by');
    }

    /**
     * Notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Connection requests sent by this user.
     */
    public function sentConnections()
    {
        return $this->hasMany(UserConnection::class, 'requester_id');
    }

    /**
     * Connection requests received by this user.
     */
    public function receivedConnections()
    {
        return $this->hasMany(UserConnection::class, 'requestee_id');
    }

    /**
     * All connections (accepted) for this user.
     */
    public function connections()
    {
        $sent = $this->sentConnections()->where('status', 'accepted')->with('requestee');
        $received = $this->receivedConnections()->where('status', 'accepted')->with('requester');

        return $sent->get()->pluck('requestee')->merge($received->get()->pluck('requester'));
    }

    /**
     * Check if connected to another user.
     */
    public function isConnectedTo(User $user): bool
    {
        return UserConnection::where(function ($query) use ($user) {
            $query->where('requester_id', $this->id)
                  ->where('requestee_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('requester_id', $user->id)
                  ->where('requestee_id', $this->id);
        })->where('status', 'accepted')->exists();
    }

    /**
     * Check if connection request is pending.
     */
    public function hasPendingConnectionWith(User $user): bool
    {
        return UserConnection::where(function ($query) use ($user) {
            $query->where('requester_id', $this->id)
                  ->where('requestee_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('requester_id', $user->id)
                  ->where('requestee_id', $this->id);
        })->where('status', 'pending')->exists();
    }

    /**
     * Get user's recent activity.
     */
    public function getRecentActivity($limit = 10)
    {
        return $this->activities()
                    ->latest()
                    ->limit($limit)
                    ->get();
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for alumni users.
     */
    public function scopeAlumni($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'alumni');
        });
    }

    /**
     * Scope for featured alumni.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for users by graduation year.
     */
    public function scopeByGraduationYear($query, $year)
    {
        return $query->where('graduation_year', $year);
    }

    /**
     * Scope for users by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope for users by location.
     */
    public function scopeByLocation($query, $city = null, $state = null, $country = null)
    {
        if ($city) {
            $query->where('city', 'like', "%{$city}%");
        }
        if ($state) {
            $query->where('state', 'like', "%{$state}%");
        }
        if ($country) {
            $query->where('country', 'like', "%{$country}%");
        }
        return $query;
    }

    /**
     * Search users by name, email, or company.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('company_name', 'like', "%{$search}%")
              ->orWhere('job_title', 'like', "%{$search}%");
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Assign default role
            if (!$user->hasAnyRole()) {
                $user->assignRole('alumni');
            }
            
            // Update profile completion
            $user->updateProfileCompletion();
        });

        static::updated(function ($user) {
            // Update profile completion when user is updated
            if ($user->wasChanged(['first_name', 'last_name', 'phone', 'date_of_birth', 'graduation_year', 'department', 'bio', 'profile_picture', 'city', 'country', 'company_name', 'job_title'])) {
                $user->updateProfileCompletion();
            }
        });
    }
}