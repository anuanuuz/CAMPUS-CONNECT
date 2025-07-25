using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusConnect.Data;
using CampusConnect.Models;

namespace CampusConnect.Controllers
{
    [Authorize]
    public class UsersController : Controller
    {
        private readonly ApplicationDbContext _context;
        private readonly UserManager<User> _userManager;

        public UsersController(ApplicationDbContext context, UserManager<User> userManager)
        {
            _context = context;
            _userManager = userManager;
        }

        // GET: Users
        public async Task<IActionResult> Index(string searchString, int? graduationYear)
        {
            var users = _context.Users.Where(u => u.IsApproved);

            if (!string.IsNullOrEmpty(searchString))
            {
                users = users.Where(u => u.FirstName.Contains(searchString) || 
                                        u.LastName.Contains(searchString) ||
                                        u.Company.Contains(searchString) ||
                                        u.JobTitle.Contains(searchString));
            }

            if (graduationYear.HasValue)
            {
                users = users.Where(u => u.GraduationYear == graduationYear);
            }

            ViewBag.SearchString = searchString;
            ViewBag.GraduationYear = graduationYear;

            return View(await users.OrderBy(u => u.FirstName).ToListAsync());
        }

        // GET: Users/Details/5
        public async Task<IActionResult> Details(string id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var user = await _context.Users
                .Include(u => u.EventRegistrations)
                    .ThenInclude(er => er.Event)
                .Include(u => u.JobPostings)
                .FirstOrDefaultAsync(u => u.Id == id);

            if (user == null)
            {
                return NotFound();
            }

            return View(user);
        }

        // GET: Users/Profile
        public async Task<IActionResult> Profile()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            if (currentUser == null)
            {
                return NotFound();
            }

            return View(currentUser);
        }

        // GET: Users/Edit
        public async Task<IActionResult> Edit()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            if (currentUser == null)
            {
                return NotFound();
            }

            return View(currentUser);
        }

        // POST: Users/Edit
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Edit([Bind("FirstName,LastName,Company,JobTitle,Department,GraduationYear,Bio,LinkedIn")] User model)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            if (currentUser == null)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                currentUser.FirstName = model.FirstName;
                currentUser.LastName = model.LastName;
                currentUser.Company = model.Company;
                currentUser.JobTitle = model.JobTitle;
                currentUser.Department = model.Department;
                currentUser.GraduationYear = model.GraduationYear;
                currentUser.Bio = model.Bio;
                currentUser.LinkedIn = model.LinkedIn;

                var result = await _userManager.UpdateAsync(currentUser);
                if (result.Succeeded)
                {
                    return RedirectToAction(nameof(Profile));
                }

                foreach (var error in result.Errors)
                {
                    ModelState.AddModelError(string.Empty, error.Description);
                }
            }

            return View(currentUser);
        }

        private bool UserExists(string id)
        {
            return _context.Users.Any(e => e.Id == id);
        }
    }
}