using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using CampusConnect.Models;
using CampusConnect.Data;

namespace CampusConnect.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;
    private readonly ApplicationDbContext _context;
    private readonly UserManager<User> _userManager;

    public HomeController(ILogger<HomeController> logger, ApplicationDbContext context, UserManager<User> userManager)
    {
        _logger = logger;
        _context = context;
        _userManager = userManager;
    }

    public async Task<IActionResult> Index()
    {
        if (User.Identity.IsAuthenticated)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            
            // Dashboard statistics
            ViewBag.TotalAlumni = await _context.Users.CountAsync(u => u.IsApproved);
            ViewBag.UpcomingEvents = await _context.Events
                .CountAsync(e => e.IsActive && e.StartDate > DateTime.Now);
            ViewBag.ActiveJobs = await _context.JobPostings
                .CountAsync(j => j.IsActive && (j.ExpiresAt == null || j.ExpiresAt > DateTime.Now));
            ViewBag.UnreadMessages = await _context.Messages
                .CountAsync(m => m.ToUserId == currentUser.Id && !m.IsRead);

            // Recent data for dashboard
            ViewBag.RecentEvents = await _context.Events
                .Include(e => e.CreatedBy)
                .Where(e => e.IsActive && e.StartDate > DateTime.Now)
                .OrderBy(e => e.StartDate)
                .Take(3)
                .ToListAsync();

            ViewBag.RecentJobs = await _context.JobPostings
                .Include(j => j.PostedBy)
                .Where(j => j.IsActive && (j.ExpiresAt == null || j.ExpiresAt > DateTime.Now))
                .OrderByDescending(j => j.CreatedAt)
                .Take(3)
                .ToListAsync();

            ViewBag.RecentMessages = await _context.Messages
                .Include(m => m.FromUser)
                .Where(m => m.ToUserId == currentUser.Id)
                .OrderByDescending(m => m.SentAt)
                .Take(3)
                .ToListAsync();
        }

        return View();
    }

    public IActionResult Privacy()
    {
        return View();
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
