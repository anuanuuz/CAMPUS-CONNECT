<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'event_date',
        'end_date',
        'location',
        'address',
        'venue_details',
        'max_attendees',
        'registration_deadline',
        'event_type',
        'category',
        'featured_image',
        'gallery_images',
        'ticket_price',
        'is_free',
        'is_virtual',
        'virtual_link',
        'virtual_platform',
        'created_by',
        'rsvp_count',
        'is_active',
        'is_public',
        'is_featured',
        'tags',
        'meta_data',
        'contact_email',
        'contact_phone',
        'website_url',
        'social_links',
        'requirements',
        'agenda',
        'speakers',
        'sponsors',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_free' => 'boolean',
        'is_virtual' => 'boolean',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'gallery_images' => 'array',
        'tags' => 'array',
        'meta_data' => 'array',
        'social_links' => 'array',
        'agenda' => 'array',
        'speakers' => 'array',
        'sponsors' => 'array',
        'ticket_price' => 'decimal:2',
        'max_attendees' => 'integer',
        'rsvp_count' => 'integer',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'is_active' => true,
        'is_public' => true,
        'is_featured' => false,
        'is_free' => true,
        'is_virtual' => false,
        'rsvp_count' => 0,
        'ticket_price' => 0.00,
        'event_type' => 'General',
    ];

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'event_date', 'location', 'is_active', 'rsvp_count'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the event creator.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get event RSVPs.
     */
    public function rsvps()
    {
        return $this->hasMany(EventRsvp::class);
    }

    /**
     * Get users who RSVP'd to this event.
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_rsvps')
                    ->withPivot(['status', 'response_date', 'comments'])
                    ->withTimestamps();
    }

    /**
     * Get confirmed attendees.
     */
    public function confirmedAttendees()
    {
        return $this->belongsToMany(User::class, 'event_rsvps')
                    ->wherePivot('status', 'going')
                    ->withPivot(['response_date', 'comments'])
                    ->withTimestamps();
    }

    /**
     * Get gallery items for this event.
     */
    public function galleryItems()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        
        return asset('images/default-event.jpg');
    }

    /**
     * Get formatted event date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->event_date->format('l, F j, Y');
    }

    /**
     * Get formatted event time.
     */
    public function getFormattedTimeAttribute(): string
    {
        $time = $this->event_date->format('g:i A');
        
        if ($this->end_date) {
            $time .= ' - ' . $this->end_date->format('g:i A');
        }
        
        return $time;
    }

    /**
     * Get formatted date and time.
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->formatted_date . ' at ' . $this->formatted_time;
    }

    /**
     * Get event duration in hours.
     */
    public function getDurationAttribute(): ?float
    {
        if (!$this->end_date) {
            return null;
        }
        
        return $this->event_date->diffInHours($this->end_date);
    }

    /**
     * Check if event is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->event_date->isFuture();
    }

    /**
     * Check if event is past.
     */
    public function isPast(): bool
    {
        return $this->event_date->isPast();
    }

    /**
     * Check if event is happening today.
     */
    public function isToday(): bool
    {
        return $this->event_date->isToday();
    }

    /**
     * Check if event is happening this week.
     */
    public function isThisWeek(): bool
    {
        return $this->event_date->isCurrentWeek();
    }

    /**
     * Check if event is happening this month.
     */
    public function isThisMonth(): bool
    {
        return $this->event_date->isCurrentMonth();
    }

    /**
     * Check if registration is open.
     */
    public function isRegistrationOpen(): bool
    {
        if (!$this->registration_deadline) {
            return $this->isUpcoming();
        }
        
        return $this->registration_deadline->isFuture() && $this->isUpcoming();
    }

    /**
     * Check if event is full.
     */
    public function isFull(): bool
    {
        if (!$this->max_attendees) {
            return false;
        }
        
        return $this->rsvp_count >= $this->max_attendees;
    }

    /**
     * Get available spots.
     */
    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->max_attendees) {
            return null;
        }
        
        return max(0, $this->max_attendees - $this->rsvp_count);
    }

    /**
     * Get event status.
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'cancelled';
        }
        
        if ($this->isPast()) {
            return 'completed';
        }
        
        if ($this->isToday()) {
            return 'happening';
        }
        
        if (!$this->isRegistrationOpen()) {
            return 'registration_closed';
        }
        
        if ($this->isFull()) {
            return 'full';
        }
        
        return 'open';
    }

    /**
     * Get event status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'success',
            'happening' => 'warning',
            'completed' => 'info',
            'full' => 'danger',
            'registration_closed' => 'secondary',
            'cancelled' => 'danger',
            default => 'primary'
        };
    }

    /**
     * Check if user has RSVP'd to this event.
     */
    public function hasUserRsvpd(User $user): bool
    {
        return $this->rsvps()->where('user_id', $user->id)->exists();
    }

    /**
     * Get user's RSVP status for this event.
     */
    public function getUserRsvpStatus(User $user): ?string
    {
        $rsvp = $this->rsvps()->where('user_id', $user->id)->first();
        return $rsvp?->status;
    }

    /**
     * RSVP user to event.
     */
    public function rsvpUser(User $user, string $status = 'going', ?string $comments = null): bool
    {
        // Check if registration is open
        if (!$this->isRegistrationOpen()) {
            return false;
        }
        
        // Check if event is full (for 'going' status)
        if ($status === 'going' && $this->isFull()) {
            return false;
        }
        
        // Update or create RSVP
        $rsvp = EventRsvp::updateOrCreate(
            ['event_id' => $this->id, 'user_id' => $user->id],
            [
                'status' => $status,
                'response_date' => now(),
                'comments' => $comments,
            ]
        );
        
        // Update RSVP count
        $this->updateRsvpCount();
        
        return true;
    }

    /**
     * Remove user's RSVP.
     */
    public function removeRsvp(User $user): bool
    {
        $deleted = $this->rsvps()->where('user_id', $user->id)->delete();
        
        if ($deleted) {
            $this->updateRsvpCount();
            return true;
        }
        
        return false;
    }

    /**
     * Update RSVP count.
     */
    public function updateRsvpCount(): void
    {
        $count = $this->rsvps()->where('status', 'going')->count();
        $this->update(['rsvp_count' => $count]);
    }

    /**
     * Scope for active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for public events.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for featured events.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>', now());
    }

    /**
     * Scope for past events.
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now());
    }

    /**
     * Scope for events happening today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('event_date', today());
    }

    /**
     * Scope for events this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('event_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope for events this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('event_date', now()->month)
                    ->whereYear('event_date', now()->year);
    }

    /**
     * Scope for events by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for events by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for virtual events.
     */
    public function scopeVirtual($query)
    {
        return $query->where('is_virtual', true);
    }

    /**
     * Scope for in-person events.
     */
    public function scopeInPerson($query)
    {
        return $query->where('is_virtual', false);
    }

    /**
     * Scope for free events.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope for paid events.
     */
    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    /**
     * Search events by title, description, or location.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for events with available spots.
     */
    public function scopeWithAvailableSpots($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('max_attendees')
              ->orWhereRaw('rsvp_count < max_attendees');
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($event) {
            // Log event creation
            activity()
                ->causedBy($event->creator)
                ->performedOn($event)
                ->log('Created event: ' . $event->title);
        });

        static::updated(function ($event) {
            // Log significant changes
            if ($event->wasChanged(['title', 'event_date', 'location', 'is_active'])) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($event)
                    ->log('Updated event: ' . $event->title);
            }
        });
    }
}