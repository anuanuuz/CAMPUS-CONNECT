using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusConnect.Data;
using CampusConnect.Models;
using Microsoft.AspNetCore.Mvc.Rendering;

namespace CampusConnect.Controllers
{
    [Authorize]
    public class MessagesController : Controller
    {
        private readonly ApplicationDbContext _context;
        private readonly UserManager<User> _userManager;

        public MessagesController(ApplicationDbContext context, UserManager<User> userManager)
        {
            _context = context;
            _userManager = userManager;
        }

        // GET: Messages
        public async Task<IActionResult> Index()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var messages = await _context.Messages
                .Include(m => m.FromUser)
                .Include(m => m.ToUser)
                .Where(m => m.ToUserId == currentUser.Id || m.FromUserId == currentUser.Id)
                .OrderByDescending(m => m.SentAt)
                .ToListAsync();

            return View(messages);
        }

        // GET: Messages/Inbox
        public async Task<IActionResult> Inbox()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var receivedMessages = await _context.Messages
                .Include(m => m.FromUser)
                .Where(m => m.ToUserId == currentUser.Id)
                .OrderByDescending(m => m.SentAt)
                .ToListAsync();

            return View(receivedMessages);
        }

        // GET: Messages/Sent
        public async Task<IActionResult> Sent()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            var sentMessages = await _context.Messages
                .Include(m => m.ToUser)
                .Where(m => m.FromUserId == currentUser.Id)
                .OrderByDescending(m => m.SentAt)
                .ToListAsync();

            return View(sentMessages);
        }

        // GET: Messages/Details/5
        public async Task<IActionResult> Details(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            var message = await _context.Messages
                .Include(m => m.FromUser)
                .Include(m => m.ToUser)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (message == null)
            {
                return NotFound();
            }

            // Check if user is authorized to view this message
            if (message.FromUserId != currentUser.Id && message.ToUserId != currentUser.Id)
            {
                return Forbid();
            }

            // Mark as read if the current user is the recipient
            if (message.ToUserId == currentUser.Id && !message.IsRead)
            {
                message.IsRead = true;
                message.ReadAt = DateTime.UtcNow;
                await _context.SaveChangesAsync();
            }

            return View(message);
        }

        // GET: Messages/Compose
        public async Task<IActionResult> Compose(string toUserId = null)
        {
            var users = await _context.Users
                .Where(u => u.IsApproved)
                .OrderBy(u => u.FirstName)
                .ThenBy(u => u.LastName)
                .ToListAsync();

            ViewBag.ToUserId = new SelectList(users, "Id", "FirstName", toUserId);
            
            var message = new Message();
            if (!string.IsNullOrEmpty(toUserId))
            {
                message.ToUserId = toUserId;
            }

            return View(message);
        }

        // POST: Messages/Compose
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Compose([Bind("Subject,Content,ToUserId")] Message message)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            message.FromUserId = currentUser.Id;

            if (ModelState.IsValid)
            {
                _context.Add(message);
                await _context.SaveChangesAsync();
                TempData["Success"] = "Message sent successfully!";
                return RedirectToAction(nameof(Sent));
            }

            var users = await _context.Users
                .Where(u => u.IsApproved)
                .OrderBy(u => u.FirstName)
                .ThenBy(u => u.LastName)
                .ToListAsync();

            ViewBag.ToUserId = new SelectList(users, "Id", "FirstName", message.ToUserId);
            return View(message);
        }

        // GET: Messages/Reply/5
        public async Task<IActionResult> Reply(int? id)
        {
            if (id == null)
            {
                return NotFound();
            }

            var originalMessage = await _context.Messages
                .Include(m => m.FromUser)
                .Include(m => m.ToUser)
                .FirstOrDefaultAsync(m => m.Id == id);

            if (originalMessage == null)
            {
                return NotFound();
            }

            var currentUser = await _userManager.GetUserAsync(User);
            
            // Check if user is authorized to reply to this message
            if (originalMessage.FromUserId != currentUser.Id && originalMessage.ToUserId != currentUser.Id)
            {
                return Forbid();
            }

            var replyMessage = new Message
            {
                ToUserId = originalMessage.FromUserId == currentUser.Id ? originalMessage.ToUserId : originalMessage.FromUserId,
                Subject = originalMessage.Subject.StartsWith("Re:") ? originalMessage.Subject : $"Re: {originalMessage.Subject}",
                Content = $"\n\n--- Original Message ---\nFrom: {originalMessage.FromUser.FirstName} {originalMessage.FromUser.LastName}\nSent: {originalMessage.SentAt:yyyy-MM-dd HH:mm}\nSubject: {originalMessage.Subject}\n\n{originalMessage.Content}"
            };

            var users = await _context.Users
                .Where(u => u.IsApproved)
                .OrderBy(u => u.FirstName)
                .ThenBy(u => u.LastName)
                .ToListAsync();

            ViewBag.ToUserId = new SelectList(users, "Id", "FirstName", replyMessage.ToUserId);
            ViewBag.OriginalMessageId = id;

            return View(replyMessage);
        }

        // POST: Messages/Reply
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Reply([Bind("Subject,Content,ToUserId")] Message message)
        {
            var currentUser = await _userManager.GetUserAsync(User);
            message.FromUserId = currentUser.Id;

            if (ModelState.IsValid)
            {
                _context.Add(message);
                await _context.SaveChangesAsync();
                TempData["Success"] = "Reply sent successfully!";
                return RedirectToAction(nameof(Sent));
            }

            var users = await _context.Users
                .Where(u => u.IsApproved)
                .OrderBy(u => u.FirstName)
                .ThenBy(u => u.LastName)
                .ToListAsync();

            ViewBag.ToUserId = new SelectList(users, "Id", "FirstName", message.ToUserId);
            return View(message);
        }

        // GET: Messages/UnreadCount
        public async Task<int> GetUnreadCount()
        {
            var currentUser = await _userManager.GetUserAsync(User);
            return await _context.Messages
                .CountAsync(m => m.ToUserId == currentUser.Id && !m.IsRead);
        }

        private bool MessageExists(int id)
        {
            return _context.Messages.Any(e => e.Id == id);
        }
    }
}