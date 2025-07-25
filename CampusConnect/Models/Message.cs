using System.ComponentModel.DataAnnotations;

namespace CampusConnect.Models
{
    public class Message
    {
        public int Id { get; set; }

        [Required]
        [StringLength(200)]
        public string Subject { get; set; } = string.Empty;

        [Required]
        [StringLength(2000)]
        public string Content { get; set; } = string.Empty;

        [Required]
        public string FromUserId { get; set; } = string.Empty;

        [Required]
        public string ToUserId { get; set; } = string.Empty;

        public DateTime SentAt { get; set; } = DateTime.UtcNow;

        public bool IsRead { get; set; } = false;

        public DateTime? ReadAt { get; set; }

        // Navigation properties
        public virtual User FromUser { get; set; } = null!;
        public virtual User ToUser { get; set; } = null!;
    }
}