using System.ComponentModel.DataAnnotations;

namespace CampusConnect.Models
{
    public class JobPosting
    {
        public int Id { get; set; }

        [Required]
        [StringLength(200)]
        public string Title { get; set; } = string.Empty;

        [Required]
        [StringLength(200)]
        public string Company { get; set; } = string.Empty;

        [Required]
        [StringLength(300)]
        public string Location { get; set; } = string.Empty;

        [Required]
        [StringLength(2000)]
        public string Description { get; set; } = string.Empty;

        [StringLength(1000)]
        public string? Requirements { get; set; }

        [StringLength(100)]
        public string? SalaryRange { get; set; }

        [Required]
        [StringLength(50)]
        public string JobType { get; set; } = string.Empty; // Full-time, Part-time, Contract, etc.

        [StringLength(200)]
        public string? ApplicationUrl { get; set; }

        [StringLength(100)]
        public string? ContactEmail { get; set; }

        public bool IsActive { get; set; } = true;

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

        public DateTime? ExpiresAt { get; set; }

        [Required]
        public string PostedByUserId { get; set; } = string.Empty;

        // Navigation properties
        public virtual User PostedBy { get; set; } = null!;
    }
}