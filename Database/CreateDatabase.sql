-- ================================================
-- Alumni Association Database Creation Script
-- ================================================

-- Create Database
IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'AlumniAssociationDB')
BEGIN
    CREATE DATABASE AlumniAssociationDB
END
GO

USE AlumniAssociationDB
GO

-- ================================================
-- Create Tables
-- ================================================

-- 1. Roles Table
CREATE TABLE Roles (
    RoleID int IDENTITY(1,1) PRIMARY KEY,
    RoleName varchar(50) NOT NULL UNIQUE,
    Description varchar(255),
    CreatedDate datetime DEFAULT GETDATE()
)

-- 2. Users Table (Main user information)
CREATE TABLE Users (
    UserID int IDENTITY(1,1) PRIMARY KEY,
    Username varchar(50) NOT NULL UNIQUE,
    Email varchar(100) NOT NULL UNIQUE,
    Password varchar(255) NOT NULL,
    FirstName varchar(50) NOT NULL,
    LastName varchar(50) NOT NULL,
    MiddleName varchar(50),
    GraduationYear varchar(10),
    Department varchar(100),
    Phone varchar(20),
    Address text,
    City varchar(50),
    State varchar(50),
    Country varchar(50) DEFAULT 'United States',
    PostalCode varchar(20),
    DateOfBirth date,
    ProfilePicture varchar(255),
    Bio text,
    LinkedInProfile varchar(255),
    FacebookProfile varchar(255),
    TwitterProfile varchar(255),
    CompanyName varchar(100),
    JobTitle varchar(100),
    Website varchar(255),
    RoleID int NOT NULL,
    IsActive bit DEFAULT 1,
    IsEmailVerified bit DEFAULT 0,
    EmailVerificationToken varchar(255),
    LastLoginDate datetime,
    CreatedDate datetime DEFAULT GETDATE(),
    UpdatedDate datetime DEFAULT GETDATE(),
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
)

-- 3. Events Table
CREATE TABLE Events (
    EventID int IDENTITY(1,1) PRIMARY KEY,
    Title varchar(200) NOT NULL,
    Description text,
    EventDate datetime NOT NULL,
    Location varchar(255),
    Address text,
    MaxAttendees int,
    RegistrationDeadline datetime,
    EventType varchar(50), -- 'Reunion', 'Workshop', 'Networking', 'Social', etc.
    ImageURL varchar(255),
    CreatedBy int NOT NULL,
    RSVP_Count int DEFAULT 0,
    IsActive bit DEFAULT 1,
    IsPublic bit DEFAULT 1,
    CreatedDate datetime DEFAULT GETDATE(),
    UpdatedDate datetime DEFAULT GETDATE(),
    FOREIGN KEY (CreatedBy) REFERENCES Users(UserID)
)

-- 4. Event RSVPs
CREATE TABLE EventRSVPs (
    RSVPID int IDENTITY(1,1) PRIMARY KEY,
    EventID int NOT NULL,
    UserID int NOT NULL,
    Status varchar(20) DEFAULT 'Going', -- 'Going', 'Maybe', 'Not Going'
    ResponseDate datetime DEFAULT GETDATE(),
    Comments text,
    FOREIGN KEY (EventID) REFERENCES Events(EventID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    UNIQUE(EventID, UserID)
)

-- 5. Messages Table
CREATE TABLE Messages (
    MessageID int IDENTITY(1,1) PRIMARY KEY,
    SenderID int NOT NULL,
    RecipientID int NOT NULL,
    Subject varchar(255),
    Body text NOT NULL,
    IsRead bit DEFAULT 0,
    IsDeleted bit DEFAULT 0,
    ParentMessageID int, -- For threaded conversations
    SentDate datetime DEFAULT GETDATE(),
    ReadDate datetime,
    FOREIGN KEY (SenderID) REFERENCES Users(UserID),
    FOREIGN KEY (RecipientID) REFERENCES Users(UserID),
    FOREIGN KEY (ParentMessageID) REFERENCES Messages(MessageID)
)

-- 6. News Articles
CREATE TABLE News (
    NewsID int IDENTITY(1,1) PRIMARY KEY,
    Title varchar(255) NOT NULL,
    Content text NOT NULL,
    Summary text,
    AuthorID int NOT NULL,
    ImageURL varchar(255),
    Category varchar(50), -- 'University', 'Alumni', 'Events', 'Career'
    Tags varchar(255),
    IsPublished bit DEFAULT 0,
    PublishedDate datetime,
    CreatedDate datetime DEFAULT GETDATE(),
    UpdatedDate datetime DEFAULT GETDATE(),
    ViewCount int DEFAULT 0,
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID)
)

-- 7. Gallery
CREATE TABLE Gallery (
    GalleryID int IDENTITY(1,1) PRIMARY KEY,
    Title varchar(255) NOT NULL,
    Description text,
    ImageURL varchar(255) NOT NULL,
    ThumbnailURL varchar(255),
    EventID int, -- Optional link to event
    UploadedBy int NOT NULL,
    IsPublic bit DEFAULT 1,
    UploadDate datetime DEFAULT GETDATE(),
    FOREIGN KEY (EventID) REFERENCES Events(EventID),
    FOREIGN KEY (UploadedBy) REFERENCES Users(UserID)
)

-- 8. Job Postings
CREATE TABLE JobPostings (
    JobID int IDENTITY(1,1) PRIMARY KEY,
    Title varchar(255) NOT NULL,
    Description text NOT NULL,
    CompanyName varchar(100) NOT NULL,
    Location varchar(255),
    SalaryRange varchar(50),
    JobType varchar(50), -- 'Full-time', 'Part-time', 'Contract', 'Internship'
    ExperienceLevel varchar(50), -- 'Entry', 'Mid', 'Senior', 'Executive'
    PostedBy int NOT NULL,
    ApplicationEmail varchar(100),
    ApplicationURL varchar(255),
    ExpiryDate datetime,
    IsActive bit DEFAULT 1,
    PostedDate datetime DEFAULT GETDATE(),
    FOREIGN KEY (PostedBy) REFERENCES Users(UserID)
)

-- 9. Activity Logs
CREATE TABLE ActivityLogs (
    ActivityID int IDENTITY(1,1) PRIMARY KEY,
    UserID int,
    Type varchar(50) NOT NULL, -- 'User', 'Event', 'Message', 'Login'
    Description text NOT NULL,
    IPAddress varchar(45),
    CreatedDate datetime DEFAULT GETDATE(),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
)

-- 10. Login Logs
CREATE TABLE LoginLogs (
    LogID int IDENTITY(1,1) PRIMARY KEY,
    Username varchar(50) NOT NULL,
    IsSuccessful bit NOT NULL,
    IPAddress varchar(45),
    AttemptDate datetime DEFAULT GETDATE()
)

-- 11. Error Logs
CREATE TABLE ErrorLogs (
    ErrorID int IDENTITY(1,1) PRIMARY KEY,
    Method varchar(100),
    ErrorMessage text NOT NULL,
    StackTrace text,
    CreatedDate datetime DEFAULT GETDATE()
)

-- 12. Notifications
CREATE TABLE Notifications (
    NotificationID int IDENTITY(1,1) PRIMARY KEY,
    UserID int NOT NULL,
    Title varchar(255) NOT NULL,
    Message text NOT NULL,
    Type varchar(50), -- 'Event', 'Message', 'News', 'System'
    IsRead bit DEFAULT 0,
    RelatedID int, -- ID of related object (EventID, MessageID, etc.)
    CreatedDate datetime DEFAULT GETDATE(),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
)

-- 13. User Connections (Alumni networking)
CREATE TABLE UserConnections (
    ConnectionID int IDENTITY(1,1) PRIMARY KEY,
    RequesterID int NOT NULL,
    RequesteeID int NOT NULL,
    Status varchar(20) DEFAULT 'Pending', -- 'Pending', 'Accepted', 'Rejected', 'Blocked'
    RequestDate datetime DEFAULT GETDATE(),
    ResponseDate datetime,
    FOREIGN KEY (RequesterID) REFERENCES Users(UserID),
    FOREIGN KEY (RequesteeID) REFERENCES Users(UserID),
    UNIQUE(RequesterID, RequesteeID)
)

-- ================================================
-- Create Indexes for Performance
-- ================================================

CREATE INDEX IX_Users_Email ON Users(Email)
CREATE INDEX IX_Users_Username ON Users(Username)
CREATE INDEX IX_Users_GraduationYear ON Users(GraduationYear)
CREATE INDEX IX_Users_Department ON Users(Department)
CREATE INDEX IX_Events_EventDate ON Events(EventDate)
CREATE INDEX IX_Events_CreatedBy ON Events(CreatedBy)
CREATE INDEX IX_Messages_SenderID ON Messages(SenderID)
CREATE INDEX IX_Messages_RecipientID ON Messages(RecipientID)
CREATE INDEX IX_Messages_SentDate ON Messages(SentDate)
CREATE INDEX IX_News_Category ON News(Category)
CREATE INDEX IX_News_PublishedDate ON News(PublishedDate)
CREATE INDEX IX_ActivityLogs_UserID ON ActivityLogs(UserID)
CREATE INDEX IX_ActivityLogs_CreatedDate ON ActivityLogs(CreatedDate)

-- ================================================
-- Insert Default Data
-- ================================================

-- Insert Roles
INSERT INTO Roles (RoleName, Description) VALUES 
('Admin', 'System Administrator with full access'),
('Alumni', 'Registered alumni member'),
('Guest', 'Guest user with limited access')

-- Insert Admin User
INSERT INTO Users (Username, Email, Password, FirstName, LastName, Department, GraduationYear, RoleID, IsActive, IsEmailVerified)
VALUES 
('admin', 'admin@alumni.edu', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', 'Admin', 'User', 'Administration', '2020', 1, 1, 1)

-- Insert Sample Alumni Users
INSERT INTO Users (Username, Email, Password, FirstName, LastName, Department, GraduationYear, Phone, City, State, CompanyName, JobTitle, RoleID, IsActive, IsEmailVerified)
VALUES 
('john.smith', 'john.smith@email.com', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', 'John', 'Smith', 'Computer Science', '2015', '555-0101', 'New York', 'NY', 'Google Inc.', 'Software Engineer', 2, 1, 1),
('sarah.johnson', 'sarah.johnson@email.com', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', 'Sarah', 'Johnson', 'Biology', '2018', '555-0102', 'Boston', 'MA', 'Harvard Medical', 'Research Scientist', 2, 1, 1),
('mike.davis', 'mike.davis@email.com', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', 'Mike', 'Davis', 'Business Administration', '2012', '555-0103', 'San Francisco', 'CA', 'TechStart Inc.', 'CEO', 2, 1, 1),
('emily.brown', 'emily.brown@email.com', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', 'Emily', 'Brown', 'Engineering', '2016', '555-0104', 'Seattle', 'WA', 'Microsoft', 'Product Manager', 2, 1, 1),
('david.wilson', 'david.wilson@email.com', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', 'David', 'Wilson', 'Marketing', '2014', '555-0105', 'Chicago', 'IL', 'ABC Marketing', 'Marketing Director', 2, 1, 1)

-- Insert Sample Events
INSERT INTO Events (Title, Description, EventDate, Location, Address, MaxAttendees, EventType, CreatedBy, IsActive, IsPublic)
VALUES 
('Annual Alumni Reunion 2024', 'Join us for our biggest gathering of the year! Reconnect with classmates, enjoy great food, and celebrate our shared memories.', DATEADD(day, 30, GETDATE()), 'University Campus, Main Hall', '123 University Ave, College Town, ST 12345', 500, 'Reunion', 1, 1, 1),
('Tech Career Workshop', 'Learn about the latest trends in technology and get career advice from successful alumni in the tech industry.', DATEADD(day, 15, GETDATE()), 'Innovation Center, Room 205', '456 Innovation Blvd, Tech City, ST 12346', 50, 'Workshop', 1, 1, 1),
('Networking Happy Hour', 'Casual networking event for alumni in the city. Great opportunity to make new connections over drinks and appetizers.', DATEADD(day, 7, GETDATE()), 'Downtown Plaza Hotel', '789 Downtown St, Metro City, ST 12347', 100, 'Networking', 1, 1, 1),
('Scholarship Fundraising Gala', 'Help us raise funds for student scholarships while enjoying an elegant evening of dinner, dancing, and entertainment.', DATEADD(day, 45, GETDATE()), 'Grand Ballroom, City Center', '321 Gala Ave, Elegant City, ST 12348', 300, 'Fundraising', 1, 1, 1)

-- Insert Sample News Articles
INSERT INTO News (Title, Content, Summary, AuthorID, Category, IsPublished, PublishedDate)
VALUES 
('University Announces New Engineering Building', 'The university has announced plans for a new state-of-the-art engineering building that will house cutting-edge research facilities and modern classrooms. Construction is expected to begin next year.', 'New engineering building to feature modern facilities and research labs.', 1, 'University', 1, GETDATE()),
('Alumni Spotlight: John Smith Receives Innovation Award', 'Class of 2015 graduate John Smith has been recognized with the Tech Innovation Award for his groundbreaking work in artificial intelligence at Google.', 'John Smith receives prestigious innovation award for AI work.', 1, 'Alumni', 1, DATEADD(day, -2, GETDATE())),
('Career Fair Set for Next Month', 'The annual alumni career fair will feature over 50 companies looking to hire talented graduates. Register now to participate.', 'Annual career fair featuring 50+ companies seeking graduates.', 1, 'Career', 1, DATEADD(day, -5, GETDATE()))

-- Insert Sample Gallery Items
INSERT INTO Gallery (Title, Description, ImageURL, ThumbnailURL, EventID, UploadedBy, IsPublic)
VALUES 
('Reunion 2023 Highlights', 'Best moments from last year\'s reunion celebration', '/Images/Gallery/reunion2023.jpg', '/Images/Gallery/thumbs/reunion2023.jpg', 1, 1, 1),
('Tech Workshop Photos', 'Photos from our recent technology workshop', '/Images/Gallery/techworkshop.jpg', '/Images/Gallery/thumbs/techworkshop.jpg', 2, 1, 1),
('Campus Architecture', 'Beautiful shots of our campus buildings', '/Images/Gallery/campus.jpg', '/Images/Gallery/thumbs/campus.jpg', NULL, 1, 1)

-- Insert Sample Job Postings
INSERT INTO JobPostings (Title, Description, CompanyName, Location, SalaryRange, JobType, ExperienceLevel, PostedBy, ApplicationEmail, ExpiryDate, IsActive)
VALUES 
('Software Developer', 'We are looking for a skilled software developer to join our team. Experience with .NET and C# preferred.', 'TechCorp Inc.', 'New York, NY', '$70,000 - $90,000', 'Full-time', 'Mid', 2, 'jobs@techcorp.com', DATEADD(day, 30, GETDATE()), 1),
('Marketing Manager', 'Seeking an experienced marketing manager to lead our digital marketing initiatives.', 'Marketing Solutions LLC', 'Chicago, IL', '$60,000 - $80,000', 'Full-time', 'Senior', 5, 'careers@marketingsolutions.com', DATEADD(day, 25, GETDATE()), 1),
('Data Scientist Intern', 'Summer internship opportunity for students interested in data science and machine learning.', 'Data Insights Corp', 'San Francisco, CA', '$20/hour', 'Internship', 'Entry', 2, 'internships@datainsights.com', DATEADD(day, 20, GETDATE()), 1)

-- Insert Sample Activity Logs
INSERT INTO ActivityLogs (UserID, Type, Description, IPAddress)
VALUES 
(2, 'User', 'John Smith updated his profile information', '192.168.1.100'),
(3, 'Event', 'Sarah Johnson RSVP''d to Tech Career Workshop', '192.168.1.101'),
(4, 'Message', 'Mike Davis sent a message to Emily Brown', '192.168.1.102'),
(5, 'User', 'Emily Brown uploaded a new profile picture', '192.168.1.103'),
(1, 'Event', 'Admin created new event: Annual Alumni Reunion 2024', '192.168.1.1')

-- ================================================
-- Create Views for Common Queries
-- ================================================

-- View for User Profile Information
CREATE VIEW vw_UserProfiles AS
SELECT 
    u.UserID,
    u.Username,
    u.Email,
    u.FirstName,
    u.LastName,
    u.MiddleName,
    u.GraduationYear,
    u.Department,
    u.Phone,
    u.City,
    u.State,
    u.Country,
    u.CompanyName,
    u.JobTitle,
    u.Bio,
    u.ProfilePicture,
    u.LinkedInProfile,
    u.FacebookProfile,
    u.TwitterProfile,
    u.Website,
    r.RoleName,
    u.IsActive,
    u.LastLoginDate,
    u.CreatedDate
FROM Users u
INNER JOIN Roles r ON u.RoleID = r.RoleID

-- View for Upcoming Events
CREATE VIEW vw_UpcomingEvents AS
SELECT 
    e.EventID,
    e.Title,
    e.Description,
    e.EventDate,
    e.Location,
    e.Address,
    e.MaxAttendees,
    e.EventType,
    e.ImageURL,
    e.RSVP_Count,
    u.FirstName + ' ' + u.LastName AS CreatedByName,
    e.CreatedDate
FROM Events e
INNER JOIN Users u ON e.CreatedBy = u.UserID
WHERE e.IsActive = 1 AND e.EventDate >= GETDATE()

-- View for Recent Activities
CREATE VIEW vw_RecentActivities AS
SELECT TOP 20
    a.ActivityID,
    a.Type,
    a.Description,
    a.CreatedDate,
    u.FirstName + ' ' + u.LastName AS UserName,
    u.ProfilePicture
FROM ActivityLogs a
LEFT JOIN Users u ON a.UserID = u.UserID
ORDER BY a.CreatedDate DESC

-- ================================================
-- Create Stored Procedures
-- ================================================

-- Procedure to get dashboard statistics
CREATE PROCEDURE sp_GetDashboardStats
AS
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM Users WHERE IsActive = 1 AND RoleID = 2) AS TotalAlumni,
        (SELECT COUNT(*) FROM Events WHERE IsActive = 1) AS TotalEvents,
        (SELECT COUNT(*) FROM Events WHERE IsActive = 1 AND EventDate >= GETDATE()) AS UpcomingEvents,
        (SELECT COUNT(*) FROM Messages WHERE SentDate >= CAST(GETDATE() AS DATE)) AS MessagesToday,
        (SELECT COUNT(*) FROM Users WHERE LastLoginDate >= DATEADD(day, -7, GETDATE())) AS ActiveThisWeek
END

-- Procedure to search alumni
CREATE PROCEDURE sp_SearchAlumni
    @SearchTerm varchar(100) = '',
    @Department varchar(100) = '',
    @GraduationYear varchar(10) = '',
    @City varchar(50) = ''
AS
BEGIN
    SELECT 
        UserID,
        FirstName,
        LastName,
        Email,
        GraduationYear,
        Department,
        City,
        State,
        CompanyName,
        JobTitle,
        ProfilePicture
    FROM Users
    WHERE IsActive = 1 
        AND RoleID = 2
        AND (@SearchTerm = '' OR FirstName LIKE '%' + @SearchTerm + '%' OR LastName LIKE '%' + @SearchTerm + '%')
        AND (@Department = '' OR Department = @Department)
        AND (@GraduationYear = '' OR GraduationYear = @GraduationYear)
        AND (@City = '' OR City = @City)
    ORDER BY LastName, FirstName
END

GO

-- ================================================
-- Database Setup Complete
-- ================================================
PRINT 'Alumni Association Database has been created successfully!'
PRINT 'Default admin user: admin / password123'
PRINT 'Sample alumni users created with password: password123'