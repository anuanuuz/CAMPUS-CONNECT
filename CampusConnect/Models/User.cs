using Microsoft.AspNetCore.Identity;
using System.ComponentModel.DataAnnotations;

namespace CampusConnect.Models
{
    public class User : IdentityUser
    {
        [Required]
        [StringLength(100)]
        public string FirstName { get; set; } = string.Empty;

        [Required]
        [StringLength(100)]
        public string LastName { get; set; } = string.Empty;

        [StringLength(200)]
        public string? Company { get; set; }

        [StringLength(200)]
        public string? JobTitle { get; set; }

        [StringLength(100)]
        public string? Department { get; set; }

        [Required]
        public int GraduationYear { get; set; }

        [StringLength(500)]
        public string? Bio { get; set; }

        [StringLength(200)]
        public string? LinkedIn { get; set; }

        [StringLength(200)]
        public string? ProfilePicture { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        
        public bool IsApproved { get; set; } = false;

        // Navigation properties
        public virtual ICollection<EventRegistration> EventRegistrations { get; set; } = new List<EventRegistration>();
        public virtual ICollection<JobPosting> JobPostings { get; set; } = new List<JobPosting>();
        public virtual ICollection<Message> SentMessages { get; set; } = new List<Message>();
        public virtual ICollection<Message> ReceivedMessages { get; set; } = new List<Message>();
    }
}