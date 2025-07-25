using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusConnect.Data;
using CampusConnect.Models;

namespace CampusConnect.Controllers
{
    [Authorize]
    public class EventsController : Controller
    {
        private readonly ApplicationDbContext _context;
        private readonly UserManager<User> _userManager;

        public EventsController(ApplicationDbContext context, UserManager<User> userManager)
        {
            _context = context;
            _userManager = userManager;
        }

        // GET: Events
        public async Task<IActionResult> Index()
        {
            var events = _context.Events
                .Include(e => e.CreatedBy)
                .Include(e => e.Registrations)
                .Where(e => e.IsActive)
                .OrderBy(e => e.StartDate);

            return View(await events.ToListAsync());
        }

        // GET: Events/Details/5
        public async Task<IActionResult> Details(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var @event = await _context.Events
                .Include(e => e.CreatedBy)
                .Include(e => e.Registrations)
                    .ThenInclude(r => r.User)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (@event == null)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            ViewBag.IsRegistered = @event.Registrations.Any(r => r.UserId == currentUser.Id);
            ViewBag.CanRegister = @event.Registrations.Count < @event.MaxAttendees && @event.StartDate > DateTime.Now;

            return View(@event);
        }

        // GET: Events/Create
        public IActionResult Create()
        {
            return View();
        }

        // POST: Events/Create
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Create([Bind("Title,Description,StartDate,EndDate,Location,MaxAttendees")] Event @event)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            @event.CreatedByUserId = currentUser.Id;

            if (ModelState.IsValid)
            {
                _context.Add(@event);
                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Index));
            }
            return View(@event);
        }

        // GET: Events/Edit/5
        public async Task<IActionResult> Edit(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var @event = await _context.Events.FindAsync(id);
            if (@event == null)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            if (@event.CreatedByUserId != currentUser.Id)
            {
                return Forbid();
            }

            return View(@event);
        }

        // POST: Events/Edit/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Edit(int id, [Bind("Id,Title,Description,StartDate,EndDate,Location,MaxAttendees,IsActive")] Event @event)
        {
            if (id != @event.Id)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            var existingEvent = await _context.Events.FindAsync(id);
            
            if (existingEvent == null || existingEvent.CreatedByUserId != currentUser.Id)
            {
                return Forbid();
            }

            if (ModelState.IsValid)
            {
                try
                {
                    existingEvent.Title = @event.Title;
                    existingEvent.Description = @event.Description;
                    existingEvent.StartDate = @event.StartDate;
                    existingEvent.EndDate = @event.EndDate;
                    existingEvent.Location = @event.Location;
                    existingEvent.MaxAttendees = @event.MaxAttendees;
                    existingEvent.IsActive = @event.IsActive;

                    _context.Update(existingEvent);
                    await _context.SaveChangesAsync();
                }
                catch (DbUpdateConcurrencyException)
                {
                    if (!EventExists(@event.Id))
                    {
                        return NotFound();
                    }
                    else
                    {
                        throw;
                    }
                }
                return RedirectToAction(nameof(Index));
            }
            return View(@event);
        }

        // POST: Events/Register/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Register(int id)
        {
            var @event = await _context.Events
                .Include(e => e.Registrations)
                .FirstOrDefaultAsync(e => e.Id == id);

            if (@event == null || !@event.IsActive || @event.StartDate <= DateTime.Now)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            
            // Check if already registered
            if (@event.Registrations.Any(r => r.UserId == currentUser.Id))
            {
                TempData["Error"] = "You are already registered for this event.";
                return RedirectToAction(nameof(Details), new { id = id });
            }

            // Check if event is full
            if (@event.Registrations.Count >= @event.MaxAttendees)
            {
                TempData["Error"] = "This event is full.";
                return RedirectToAction(nameof(Details), new { id = id });
            }

            var registration = new EventRegistration
            {
                EventId = id,
                UserId = currentUser.Id
            };

            _context.EventRegistrations.Add(registration);
            await _context.SaveChangesAsync();

            TempData["Success"] = "You have successfully registered for this event!";
            return RedirectToAction(nameof(Details), new { id = id });
        }

        // POST: Events/Unregister/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Unregister(int id)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var registration = await _context.EventRegistrations
                .FirstOrDefaultAsync(r => r.EventId == id && r.UserId == currentUser.Id);

            if (registration != null)
            {
                _context.EventRegistrations.Remove(registration);
                await _context.SaveChangesAsync();
                TempData["Success"] = "You have successfully unregistered from this event.";
            }

            return RedirectToAction(nameof(Details), new { id = id });
        }

        // GET: Events/MyEvents
        public async Task<IActionResult> MyEvents()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var userEvents = await _context.EventRegistrations
                .Include(er => er.Event)
                .Where(er => er.UserId == currentUser.Id)
                .Select(er => er.Event)
                .OrderBy(e => e.StartDate)
                .ToListAsync();

            return View(userEvents);
        }

        private bool EventExists(int id)
        {
            return _context.Events.Any(e => e.Id == id);
        }
    }
}