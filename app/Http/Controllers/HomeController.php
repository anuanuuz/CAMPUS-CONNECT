<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\{User, Event, News, Gallery};
use App\Mail\ContactFormMail;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Cache statistics for performance
        $stats = Cache::remember('homepage_stats', 300, function () {
            return [
                'total_alumni' => User::alumni()->active()->count(),
                'upcoming_events' => Event::active()->upcoming()->count(),
                'total_events' => Event::active()->count(),
                'recent_graduates' => User::alumni()
                    ->where('graduation_year', '>=', Carbon::now()->year - 2)
                    ->count(),
            ];
        });

        // Get upcoming events for homepage
        $upcomingEvents = Cache::remember('homepage_events', 600, function () {
            return Event::active()
                ->public()
                ->upcoming()
                ->with(['creator:id,first_name,last_name'])
                ->orderBy('event_date')
                ->limit(6)
                ->get();
        });

        // Get recent news
        $recentNews = Cache::remember('homepage_news', 600, function () {
            return News::published()
                ->with(['author:id,first_name,last_name'])
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        });

        // Get featured alumni
        $featuredAlumni = Cache::remember('featured_alumni', 1800, function () {
            return User::alumni()
                ->active()
                ->featured()
                ->limit(6)
                ->get();
        });

        return view('home', compact('stats', 'upcomingEvents', 'recentNews', 'featuredAlumni'));
    }

    /**
     * Show user dashboard after login.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // User's personalized statistics
        $userStats = [
            'profile_completion' => $user->profile_completion,
            'connections_count' => $user->connections()->count(),
            'events_attended' => $user->rsvpEvents()->wherePivot('status', 'going')->count(),
            'messages_unread' => $user->receivedMessages()->unread()->count(),
        ];

        // Upcoming events user is attending
        $myUpcomingEvents = $user->rsvpEvents()
            ->wherePivot('status', 'going')
            ->where('event_date', '>', now())
            ->orderBy('event_date')
            ->limit(5)
            ->get();

        // Suggested events (based on user's interests/location/department)
        $suggestedEvents = Event::active()
            ->public()
            ->upcoming()
            ->where('department', $user->department)
            ->orWhere('location', 'like', '%' . $user->city . '%')
            ->whereNotIn('id', $user->rsvpEvents->pluck('id'))
            ->limit(4)
            ->get();

        // Recent activity from connections
        $recentActivity = $this->getRecentActivity($user);

        // Connection suggestions
        $connectionSuggestions = $this->getConnectionSuggestions($user);

        // Quick stats for dashboard widgets
        $quickStats = [
            'total_alumni' => User::alumni()->active()->count(),
            'online_now' => User::active()
                ->where('last_login_at', '>=', now()->subMinutes(15))
                ->count(),
            'events_this_month' => Event::active()
                ->whereMonth('event_date', now()->month)
                ->count(),
            'new_members_this_month' => User::alumni()
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('dashboard', compact(
            'user',
            'userStats',
            'myUpcomingEvents',
            'suggestedEvents',
            'recentActivity',
            'connectionSuggestions',
            'quickStats'
        ));
    }

    /**
     * Show about page.
     */
    public function about()
    {
        $stats = [
            'founded_year' => 1999,
            'total_alumni' => User::alumni()->count(),
            'countries' => User::alumni()->distinct('country')->count('country'),
            'events_hosted' => Event::count(),
            'success_stories' => 500, // This could come from a dedicated model
        ];

        $milestones = [
            ['year' => 1999, 'event' => 'Alumni Association Founded'],
            ['year' => 2005, 'event' => 'First International Reunion'],
            ['year' => 2010, 'event' => 'Digital Platform Launch'],
            ['year' => 2015, 'event' => '1000+ Members Milestone'],
            ['year' => 2020, 'event' => 'Virtual Events Program'],
            ['year' => 2024, 'event' => 'Modern Portal Launch'],
        ];

        $leadership = [
            [
                'name' => 'Dr. John Smith',
                'position' => 'President',
                'class' => '1995',
                'bio' => 'Leading technology executive with 25+ years of experience.',
                'image' => 'leadership/john-smith.jpg'
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => 'Vice President',
                'class' => '2001',
                'bio' => 'Renowned educator and community leader.',
                'image' => 'leadership/sarah-johnson.jpg'
            ],
            [
                'name' => 'Michael Davis',
                'position' => 'Secretary',
                'class' => '2003',
                'bio' => 'Financial consultant and philanthropist.',
                'image' => 'leadership/michael-davis.jpg'
            ],
        ];

        return view('pages.about', compact('stats', 'milestones', 'leadership'));
    }

    /**
     * Show contact page.
     */
    public function contact()
    {
        $contactInfo = [
            'address' => '123 University Drive, College Town, ST 12345',
            'phone' => '+1 (555) 123-4567',
            'email' => 'info@alumni.university.edu',
            'hours' => 'Monday - Friday: 9:00 AM - 5:00 PM',
        ];

        $departments = [
            'general' => 'General Inquiries',
            'membership' => 'Membership Services',
            'events' => 'Event Planning',
            'donations' => 'Donations & Giving',
            'careers' => 'Career Services',
            'technical' => 'Technical Support',
        ];

        return view('pages.contact', compact('contactInfo', 'departments'));
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|in:general,membership,events,donations,careers,technical',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
            'captcha' => 'required|captcha', // If using captcha
        ]);

        try {
            // Send email to appropriate department
            $departmentEmails = [
                'general' => config('mail.from.address'),
                'membership' => 'membership@alumni.university.edu',
                'events' => 'events@alumni.university.edu',
                'donations' => 'giving@alumni.university.edu',
                'careers' => 'careers@alumni.university.edu',
                'technical' => 'support@alumni.university.edu',
            ];

            $recipient = $departmentEmails[$validated['department']] ?? config('mail.from.address');

            Mail::to($recipient)->send(new ContactFormMail($validated));

            // Log the contact form submission
            activity()
                ->withProperties($validated)
                ->log('Contact form submitted');

            return redirect()
                ->route('contact')
                ->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            \Log::error('Contact form submission failed: ' . $e->getMessage());
            
            return redirect()
                ->route('contact')
                ->with('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }

    /**
     * Show privacy policy.
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Show terms of service.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Show help/FAQ page.
     */
    public function help()
    {
        $faqs = [
            [
                'category' => 'Account & Profile',
                'questions' => [
                    [
                        'question' => 'How do I update my profile information?',
                        'answer' => 'Navigate to your profile page and click the "Edit Profile" button. You can update your personal information, professional details, and privacy settings.'
                    ],
                    [
                        'question' => 'How do I change my password?',
                        'answer' => 'Go to your profile settings and look for the "Change Password" section. Enter your current password and choose a new one.'
                    ],
                    [
                        'question' => 'Can I control who sees my profile?',
                        'answer' => 'Yes! In your privacy settings, you can control the visibility of your profile, contact information, and other personal details.'
                    ],
                ]
            ],
            [
                'category' => 'Events & Networking',
                'questions' => [
                    [
                        'question' => 'How do I RSVP to events?',
                        'answer' => 'Visit the event page and click the "RSVP" button. You can choose to attend, decline, or mark as interested.'
                    ],
                    [
                        'question' => 'Can I suggest new events?',
                        'answer' => 'Absolutely! Use the "Suggest Event" feature in the events section. All suggestions are reviewed by our events team.'
                    ],
                    [
                        'question' => 'How do I connect with other alumni?',
                        'answer' => 'Browse the alumni directory, send connection requests, and engage in event discussions and messages.'
                    ],
                ]
            ],
            [
                'category' => 'Technical Support',
                'questions' => [
                    [
                        'question' => 'I\'m having trouble logging in',
                        'answer' => 'Try resetting your password using the "Forgot Password" link. If you still have issues, contact our technical support team.'
                    ],
                    [
                        'question' => 'The website is not working properly',
                        'answer' => 'Please try clearing your browser cache and cookies. If the problem persists, report it through our contact form.'
                    ],
                    [
                        'question' => 'How do I upload photos?',
                        'answer' => 'You can upload photos to your profile, event galleries, and share them in the community gallery section.'
                    ],
                ]
            ],
        ];

        return view('pages.help', compact('faqs'));
    }

    /**
     * Show donation page.
     */
    public function donate()
    {
        $donationTiers = [
            [
                'name' => 'Supporter',
                'amount' => 25,
                'benefits' => ['Official thank you', 'Alumni newsletter'],
                'color' => 'primary'
            ],
            [
                'name' => 'Advocate',
                'amount' => 100,
                'benefits' => ['All Supporter benefits', 'Event priority access', 'Alumni pin'],
                'color' => 'success'
            ],
            [
                'name' => 'Champion',
                'amount' => 250,
                'benefits' => ['All Advocate benefits', 'VIP event access', 'Annual report'],
                'color' => 'warning'
            ],
            [
                'name' => 'Legacy',
                'amount' => 500,
                'benefits' => ['All Champion benefits', 'Advisory board consideration', 'Legacy plaque'],
                'color' => 'danger'
            ],
        ];

        $impactStats = [
            'scholarships_funded' => 45,
            'events_supported' => 150,
            'programs_created' => 12,
            'students_helped' => 300,
        ];

        return view('pages.donate', compact('donationTiers', 'impactStats'));
    }

    /**
     * Get recent activity for user's dashboard.
     */
    private function getRecentActivity($user)
    {
        // Get activity from user's connections
        $connectionIds = $user->connections()->pluck('id');
        
        return collect()
            ->merge(
                // Recent events attended by connections
                Event::whereHas('attendees', function ($query) use ($connectionIds) {
                    $query->whereIn('user_id', $connectionIds);
                })->with('attendees')->latest()->limit(5)->get()
            )
            ->merge(
                // Recent profile updates from connections
                User::whereIn('id', $connectionIds)
                    ->where('updated_at', '>=', now()->subWeek())
                    ->latest('updated_at')
                    ->limit(5)
                    ->get()
            )
            ->sortByDesc('updated_at')
            ->take(10);
    }

    /**
     * Get connection suggestions for user.
     */
    private function getConnectionSuggestions($user)
    {
        $currentConnections = $user->connections()->pluck('id')->toArray();
        $currentConnections[] = $user->id; // Exclude self

        return User::alumni()
            ->active()
            ->whereNotIn('id', $currentConnections)
            ->where(function ($query) use ($user) {
                $query->where('graduation_year', $user->graduation_year)
                      ->orWhere('department', $user->department)
                      ->orWhere('city', $user->city)
                      ->orWhere('company_name', $user->company_name);
            })
            ->limit(6)
            ->get();
    }
}