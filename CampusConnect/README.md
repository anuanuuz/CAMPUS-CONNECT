# Campus Connect - Alumni Association Platform

Campus Connect is a comprehensive ASP.NET Core web application designed for college alumni associations. It provides a platform for alumni to connect, organize events, share job opportunities, and maintain professional networks.

## Features

### 🏫 Three Main Modules

#### 1. **User Management Module**
- **Alumni Profiles**: Complete user profiles with professional information
- **Authentication & Authorization**: Secure login system with ASP.NET Core Identity
- **Profile Management**: Edit personal and professional details
- **Alumni Directory**: Search and browse alumni by graduation year, company, or location
- **Account Approval**: Admin approval system for new registrations

#### 2. **Event Management Module**
- **Event Creation**: Alumni can create and organize events
- **Event Registration**: RSVP system with capacity management
- **Event Browsing**: View upcoming and past events
- **Event Management**: Edit and manage events you've created
- **Registration Tracking**: Track attendance and manage registrations

#### 3. **Networking Module**
- **Job Board**: Post and browse job opportunities
- **Job Management**: Create, edit, and manage job postings
- **Messaging System**: Direct communication between alumni
- **Professional Networking**: Connect with fellow alumni for career opportunities

## Technology Stack

- **Framework**: ASP.NET Core 8.0 MVC
- **Database**: SQLite with Entity Framework Core
- **Authentication**: ASP.NET Core Identity
- **Frontend**: Bootstrap 5, Font Awesome icons
- **Language**: C# (.NET 8.0)

## Database Schema

### Core Models
- **User**: Extended IdentityUser with alumni-specific fields
- **Event**: Event management with registration tracking
- **EventRegistration**: Many-to-many relationship for event attendees
- **JobPosting**: Job opportunities posted by alumni
- **Message**: Internal messaging system

## Getting Started

### Prerequisites
- .NET 8.0 SDK
- SQLite (included with .NET)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd CampusConnect
   ```

2. **Restore packages**
   ```bash
   dotnet restore
   ```

3. **Build the project**
   ```bash
   dotnet build
   ```

4. **Run the application**
   ```bash
   dotnet run
   ```

5. **Access the application**
   - Open your browser and navigate to `http://localhost:5000`
   - Register as a new user or use the existing accounts

### Database Setup

The application uses SQLite with Entity Framework Core. The database will be created automatically when you first run the application.

**Connection String**: `Data Source=campusconnect.db`

## Project Structure

```
CampusConnect/
├── Controllers/           # MVC Controllers for each module
│   ├── HomeController.cs     # Dashboard and home page
│   ├── UsersController.cs    # User management
│   ├── EventsController.cs   # Event management
│   ├── JobsController.cs     # Job board functionality
│   └── MessagesController.cs # Messaging system
├── Models/               # Data models and entities
│   ├── User.cs              # Extended user model
│   ├── Event.cs             # Event entity
│   ├── EventRegistration.cs # Event registration entity
│   ├── JobPosting.cs        # Job posting entity
│   └── Message.cs           # Message entity
├── Data/                 # Database context and configuration
│   └── ApplicationDbContext.cs
├── Views/                # Razor views and templates
│   ├── Home/               # Dashboard views
│   ├── Users/              # User management views
│   ├── Events/             # Event views
│   ├── Jobs/               # Job board views
│   ├── Messages/           # Messaging views
│   └── Shared/             # Shared layout and components
└── wwwroot/              # Static files (CSS, JS, images)
```

## Key Features in Detail

### Dashboard
- Real-time statistics (total alumni, upcoming events, active jobs, unread messages)
- Quick actions for common tasks
- Recent activity feeds
- Responsive design for mobile and desktop

### User Management
- **Registration**: New users can register and await approval
- **Profile Management**: Complete professional profiles
- **Alumni Directory**: Search and filter alumni
- **Role-based Access**: Different permissions for users and administrators

### Event Management
- **Event Creation**: Rich event details with location and capacity
- **Registration System**: One-click registration with capacity management
- **Event Types**: Support for various event types (reunions, networking, workshops)
- **Management Tools**: Event creators can manage registrations and attendance

### Job Board
- **Job Posting**: Alumni can post job opportunities
- **Job Search**: Filter by location, job type, and keywords
- **Application Tracking**: Direct links to application portals
- **Expiration Management**: Automatic handling of expired job posts

### Messaging System
- **Direct Messaging**: One-on-one communication between alumni
- **Message Threading**: Reply functionality with message history
- **Read Status**: Track read/unread messages
- **Professional Networking**: Contact alumni for opportunities

## Configuration

### Key Settings in `appsettings.json`

```json
{
  "ConnectionStrings": {
    "DefaultConnection": "Data Source=campusconnect.db"
  }
}
```

### Identity Configuration
- Password requirements: Minimum 6 characters, requires digit
- Account confirmation: Disabled for ease of development
- Cookie-based authentication

## Security Features

- **Authentication**: ASP.NET Core Identity with secure password hashing
- **Authorization**: Role-based access control
- **Data Protection**: Form validation and anti-forgery tokens
- **Input Validation**: Server-side validation on all forms
- **SQL Injection Protection**: Entity Framework parameterized queries

## Future Enhancements

- [ ] Email notifications for events and messages
- [ ] Photo upload for profiles and events
- [ ] Advanced search and filtering
- [ ] Social media integration
- [ ] Mobile application
- [ ] Payment integration for event tickets
- [ ] Alumni news and announcements
- [ ] Calendar integration
- [ ] Export functionality for contact lists

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Screenshots

*Note: Add screenshots of the application once running*

- Dashboard with statistics and quick actions
- Alumni directory with search functionality
- Event management interface
- Job board with filtering options
- Messaging system interface

---

**Campus Connect** - Connecting alumni, fostering relationships, building careers.