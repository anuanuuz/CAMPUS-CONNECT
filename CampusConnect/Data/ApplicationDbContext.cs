using Microsoft.AspNetCore.Identity.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore;
using CampusConnect.Models;

namespace CampusConnect.Data
{
    public class ApplicationDbContext : IdentityDbContext<User>
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options) : base(options)
        {
        }

        public DbSet<Event> Events { get; set; }
        public DbSet<EventRegistration> EventRegistrations { get; set; }
        public DbSet<JobPosting> JobPostings { get; set; }
        public DbSet<Message> Messages { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            // Configure Event relationships
            modelBuilder.Entity<Event>()
                .HasOne(e => e.CreatedBy)
                .WithMany()
                .HasForeignKey(e => e.CreatedByUserId)
                .OnDelete(DeleteBehavior.Restrict);

            // Configure EventRegistration relationships
            modelBuilder.Entity<EventRegistration>()
                .HasOne(er => er.Event)
                .WithMany(e => e.Registrations)
                .HasForeignKey(er => er.EventId)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<EventRegistration>()
                .HasOne(er => er.User)
                .WithMany(u => u.EventRegistrations)
                .HasForeignKey(er => er.UserId)
                .OnDelete(DeleteBehavior.Cascade);

            // Configure JobPosting relationships
            modelBuilder.Entity<JobPosting>()
                .HasOne(jp => jp.PostedBy)
                .WithMany(u => u.JobPostings)
                .HasForeignKey(jp => jp.PostedByUserId)
                .OnDelete(DeleteBehavior.Restrict);

            // Configure Message relationships
            modelBuilder.Entity<Message>()
                .HasOne(m => m.FromUser)
                .WithMany(u => u.SentMessages)
                .HasForeignKey(m => m.FromUserId)
                .OnDelete(DeleteBehavior.Restrict);

            modelBuilder.Entity<Message>()
                .HasOne(m => m.ToUser)
                .WithMany(u => u.ReceivedMessages)
                .HasForeignKey(m => m.ToUserId)
                .OnDelete(DeleteBehavior.Restrict);

            // Add unique constraint for event registration
            modelBuilder.Entity<EventRegistration>()
                .HasIndex(er => new { er.EventId, er.UserId })
                .IsUnique();
        }
    }
}