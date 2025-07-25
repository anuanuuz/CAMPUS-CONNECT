<%@ Page Title="Home" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<asp:Content ID="Content1" ContentPlaceHolderID="TitleContent" Runat="Server">
    Home
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="HeadContent" Runat="Server">
    <style>
        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%), url('Images/campus-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 4rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-image {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }
        
        .testimonial-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 3rem 0;
            margin: 3rem 0;
            border-radius: 20px;
        }
    </style>
</asp:Content>

<asp:Content ID="Content3" ContentPlaceHolderID="MainContent" Runat="Server">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Welcome to Alumni Portal</h1>
            <p class="hero-subtitle">Connecting Graduates • Building Networks • Creating Opportunities</p>
            <div style="margin-top: 2rem;">
                <asp:Panel ID="pnlGuestActions" runat="server" Visible="true">
                    <a href="Register.aspx" class="btn btn-large" style="margin-right: 1rem;">
                        <i class="fas fa-user-plus"></i> Join Our Community
                    </a>
                    <a href="Login.aspx" class="btn btn-secondary btn-large">
                        <i class="fas fa-sign-in-alt"></i> Member Login
                    </a>
                </asp:Panel>
                <asp:Panel ID="pnlUserActions" runat="server" Visible="false">
                    <a href="Alumni/Profile.aspx" class="btn btn-large" style="margin-right: 1rem;">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="Events/Events.aspx" class="btn btn-secondary btn-large">
                        <i class="fas fa-calendar"></i> Browse Events
                    </a>
                </asp:Panel>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Alumni Members</h3>
                    <div class="widget-value">
                        <asp:Label ID="lblTotalAlumni" runat="server" Text="0"></asp:Label>
                    </div>
                    <p>Active members in our community</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Upcoming Events</h3>
                    <div class="widget-value">
                        <asp:Label ID="lblUpcomingEvents" runat="server" Text="0"></asp:Label>
                    </div>
                    <p>Events planned for this year</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Years of Legacy</h3>
                    <div class="widget-value">25+</div>
                    <p>Connecting alumni since 1999</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3>Success Stories</h3>
                    <div class="widget-value">500+</div>
                    <p>Career advancements through networking</p>
                </div>
            </div>
        </section>

        <!-- Featured Events Section -->
        <section class="events-section">
            <div class="content-header" style="border-radius: 15px; margin-bottom: 2rem;">
                <h2><i class="fas fa-star"></i> Upcoming Events</h2>
                <p>Don't miss these exciting opportunities to connect and grow</p>
            </div>
            
            <asp:UpdatePanel ID="upEvents" runat="server">
                <ContentTemplate>
                    <asp:Repeater ID="rptUpcomingEvents" runat="server">
                        <HeaderTemplate>
                            <div class="grid grid-2">
                        </HeaderTemplate>
                        <ItemTemplate>
                            <div class="event-card">
                                <div class="event-date">
                                    <%# DateTime.Parse(Eval("EventDate").ToString()).ToString("MMM dd, yyyy") %>
                                </div>
                                <h3 class="event-title"><%# Eval("Title") %></h3>
                                <p class="event-description"><%# Eval("Description") %></p>
                                <p style="color: #667eea; font-weight: 500;">
                                    <i class="fas fa-map-marker-alt"></i> <%# Eval("Location") %>
                                </p>
                                <div style="margin-top: 1rem;">
                                    <a href="Events/EventDetails.aspx?id=<%# Eval("EventID") %>" class="btn">
                                        <i class="fas fa-info-circle"></i> Learn More
                                    </a>
                                </div>
                            </div>
                        </ItemTemplate>
                        <FooterTemplate>
                            </div>
                        </FooterTemplate>
                    </asp:Repeater>
                    
                    <asp:Panel ID="pnlNoEvents" runat="server" Visible="false" CssClass="card" style="text-align: center; padding: 3rem;">
                        <i class="fas fa-calendar-times" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <h3>No Upcoming Events</h3>
                        <p>Check back soon for exciting events and networking opportunities!</p>
                    </asp:Panel>
                </ContentTemplate>
            </asp:UpdatePanel>
            
            <div style="text-align: center; margin-top: 2rem;">
                <a href="Events/Events.aspx" class="btn btn-large">
                    <i class="fas fa-calendar"></i> View All Events
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="content-header" style="border-radius: 15px; margin-bottom: 2rem;">
                <h2><i class="fas fa-rocket"></i> Platform Features</h2>
                <p>Discover what makes our alumni community special</p>
            </div>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-image">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-body">
                        <h3>Alumni Directory</h3>
                        <p>Connect with fellow graduates, search by graduation year, department, or location. Build your professional network.</p>
                        <a href="Alumni/Directory.aspx" class="btn">Explore Directory</a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-image">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-body">
                        <h3>Event Management</h3>
                        <p>Stay updated with alumni gatherings, workshops, and networking events. RSVP and never miss an opportunity.</p>
                        <a href="Events/Events.aspx" class="btn">View Events</a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-image">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="card-body">
                        <h3>Messaging System</h3>
                        <p>Send private messages to other alumni, create groups, and maintain your professional relationships.</p>
                        <a href="Messages/Inbox.aspx" class="btn">Start Messaging</a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-image">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="card-body">
                        <h3>News & Updates</h3>
                        <p>Stay informed about university news, alumni achievements, and community updates.</p>
                        <a href="News.aspx" class="btn">Read News</a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-image">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="card-body">
                        <h3>Career Opportunities</h3>
                        <p>Access job postings from fellow alumni, mentorship programs, and career development resources.</p>
                        <a href="Careers.aspx" class="btn">Explore Careers</a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-image">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="card-body">
                        <h3>Photo Gallery</h3>
                        <p>Browse photos from past events, graduation ceremonies, and community gatherings.</p>
                        <a href="Gallery.aspx" class="btn">View Gallery</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonial-section">
            <div class="container">
                <h2 style="text-align: center; margin-bottom: 2rem;">
                    <i class="fas fa-quote-left"></i> What Our Alumni Say
                </h2>
                <div class="grid grid-3">
                    <div style="text-align: center;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">👨‍💼</div>
                        <h4>John Smith, Class of 2015</h4>
                        <p>"The alumni network helped me land my dream job at Google. The connections I made here are invaluable!"</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">👩‍🔬</div>
                        <h4>Sarah Johnson, Class of 2018</h4>
                        <p>"From student to PhD researcher - the mentorship I received through this platform changed my life."</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">👨‍💻</div>
                        <h4>Mike Davis, Class of 2012</h4>
                        <p>"Starting my own tech company was possible because of the support and guidance from fellow alumni."</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section style="text-align: center; margin: 3rem 0;">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem;">
                <h2>Ready to Join Our Community?</h2>
                <p style="font-size: 1.2rem; margin: 1rem 0;">Connect with thousands of alumni worldwide and unlock endless opportunities.</p>
                <asp:Panel ID="pnlCTAGuest" runat="server" Visible="true">
                    <a href="Register.aspx" class="btn" style="background: white; color: #667eea; margin: 1rem;">
                        <i class="fas fa-rocket"></i> Get Started Today
                    </a>
                </asp:Panel>
                <asp:Panel ID="pnlCTAUser" runat="server" Visible="false">
                    <a href="Alumni/Profile.aspx" class="btn" style="background: white; color: #667eea; margin: 1rem;">
                        <i class="fas fa-user-edit"></i> Complete Your Profile
                    </a>
                </asp:Panel>
            </div>
        </section>
    </div>
</asp:Content>