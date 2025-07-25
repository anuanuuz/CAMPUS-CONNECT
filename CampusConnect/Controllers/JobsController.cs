using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusConnect.Data;
using CampusConnect.Models;

namespace CampusConnect.Controllers
{
    [Authorize]
    public class JobsController : Controller
    {
        private readonly ApplicationDbContext _context;
        private readonly UserManager<User> _userManager;

        public JobsController(ApplicationDbContext context, UserManager<User> userManager)
        {
            _context = context;
            _userManager = userManager;
        }

        // GET: Jobs
        public async Task<IActionResult> Index(string searchString, string jobType, string location)
        {
            var jobs = _context.JobPostings
                .Include(j => j.PostedBy)
                .Where(j => j.IsActive && (j.ExpiresAt == null || j.ExpiresAt > DateTime.Now));

            if (!string.IsNullOrEmpty(searchString))
            {
                jobs = jobs.Where(j => j.Title.Contains(searchString) || 
                                      j.Company.Contains(searchString) ||
                                      j.Description.Contains(searchString));
            }

            if (!string.IsNullOrEmpty(jobType))
            {
                jobs = jobs.Where(j => j.JobType == jobType);
            }

            if (!string.IsNullOrEmpty(location))
            {
                jobs = jobs.Where(j => j.Location.Contains(location));
            }

            ViewBag.SearchString = searchString;
            ViewBag.JobType = jobType;
            ViewBag.Location = location;
            ViewBag.JobTypes = new List<string> { "Full-time", "Part-time", "Contract", "Internship", "Remote" };

            return View(await jobs.OrderByDescending(j => j.CreatedAt).ToListAsync());
        }

        // GET: Jobs/Details/5
        public async Task<IActionResult> Details(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var jobPosting = await _context.JobPostings
                .Include(j => j.PostedBy)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (jobPosting == null)
            {
                return NotFound();
            }

            return View(jobPosting);
        }

        // GET: Jobs/Create
        public IActionResult Create()
        {
            ViewBag.JobTypes = new List<string> { "Full-time", "Part-time", "Contract", "Internship", "Remote" };
            return View();
        }

        // POST: Jobs/Create
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Create([Bind("Title,Company,Location,Description,Requirements,SalaryRange,JobType,ApplicationUrl,ContactEmail,ExpiresAt")] JobPosting jobPosting)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            jobPosting.PostedByUserId = currentUser.Id;

            if (ModelState.IsValid)
            {
                _context.Add(jobPosting);
                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Index));
            }

            ViewBag.JobTypes = new List<string> { "Full-time", "Part-time", "Contract", "Internship", "Remote" };
            return View(jobPosting);
        }

        // GET: Jobs/Edit/5
        public async Task<IActionResult> Edit(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var jobPosting = await _context.JobPostings.FindAsync(id);
            if (jobPosting == null)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            if (jobPosting.PostedByUserId != currentUser.Id)
            {
                return Forbid();
            }

            ViewBag.JobTypes = new List<string> { "Full-time", "Part-time", "Contract", "Internship", "Remote" };
            return View(jobPosting);
        }

        // POST: Jobs/Edit/5
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Edit(int id, [Bind("Id,Title,Company,Location,Description,Requirements,SalaryRange,JobType,ApplicationUrl,ContactEmail,IsActive,ExpiresAt")] JobPosting jobPosting)
        {
            if (id != jobPosting.Id)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            var existingJob = await _context.JobPostings.FindAsync(id);

            if (existingJob == null || existingJob.PostedByUserId != currentUser.Id)
            {
                return Forbid();
            }

            if (ModelState.IsValid)
            {
                try
                {
                    existingJob.Title = jobPosting.Title;
                    existingJob.Company = jobPosting.Company;
                    existingJob.Location = jobPosting.Location;
                    existingJob.Description = jobPosting.Description;
                    existingJob.Requirements = jobPosting.Requirements;
                    existingJob.SalaryRange = jobPosting.SalaryRange;
                    existingJob.JobType = jobPosting.JobType;
                    existingJob.ApplicationUrl = jobPosting.ApplicationUrl;
                    existingJob.ContactEmail = jobPosting.ContactEmail;
                    existingJob.IsActive = jobPosting.IsActive;
                    existingJob.ExpiresAt = jobPosting.ExpiresAt;

                    _context.Update(existingJob);
                    await _context.SaveChangesAsync();
                }
                catch (DbUpdateConcurrencyException)
                {
                    if (!JobPostingExists(jobPosting.Id))
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

            ViewBag.JobTypes = new List<string> { "Full-time", "Part-time", "Contract", "Internship", "Remote" };
            return View(jobPosting);
        }

        // GET: Jobs/MyJobs
        public async Task<IActionResult> MyJobs()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var myJobs = await _context.JobPostings
                .Where(j => j.PostedByUserId == currentUser.Id)
                .OrderByDescending(j => j.CreatedAt)
                .ToListAsync();

            return View(myJobs);
        }

        // POST: Jobs/Delete/5
        [HttpPost, ActionName("Delete")]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteConfirmed(int id)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var jobPosting = await _context.JobPostings.FindAsync(id);

            if (jobPosting != null && jobPosting.PostedByUserId == currentUser.Id)
            {
                _context.JobPostings.Remove(jobPosting);
                await _context.SaveChangesAsync();
            }

            return RedirectToAction(nameof(MyJobs));
        }

        private bool JobPostingExists(int id)
        {
            return _context.JobPostings.Any(e => e.Id == id);
        }
    }
}