using System.ComponentModel.DataAnnotations;

namespace CampusConnect.Models
{
    public class EventRegistration
    {
        public int Id { get; set; }

        [Required]
        public int EventId { get; set; }

        [Required]
        public string UserId { get; set; } = string.Empty;

        public DateTime RegistrationDate { get; set; } = DateTime.UtcNow;

        public bool IsAttended { get; set; } = false;

        [StringLength(500)]
        public string? Notes { get; set; }

        // Navigation properties
        public virtual Event Event { get; set; } = null!;
        public virtual User User { get; set; } = null!;
    }
}