using System.ComponentModel.DataAnnotations;

namespace CampusConnect.Models
{
    public class Event
    {
        public int Id { get; set; }

        [Required]
        [StringLength(200)]
        public string Title { get; set; } = string.Empty;

        [Required]
        [StringLength(1000)]
        public string Description { get; set; } = string.Empty;

        [Required]
        public DateTime StartDate { get; set; }

        [Required]
        public DateTime EndDate { get; set; }

        [Required]
        [StringLength(300)]
        public string Location { get; set; } = string.Empty;

        [Range(1, 10000)]
        public int MaxAttendees { get; set; }

        [StringLength(200)]
        public string? EventImage { get; set; }

        public bool IsActive { get; set; } = true;

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

        [Required]
        public string CreatedByUserId { get; set; } = string.Empty;

        // Navigation properties
        public virtual User CreatedBy { get; set; } = null!;
        public virtual ICollection<EventRegistration> Registrations { get; set; } = new List<EventRegistration>();
    }
}