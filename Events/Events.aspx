<%@ Page Title="Events" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Events.aspx.cs" Inherits="Events_Events" %>

<asp:Content ID="Content1" ContentPlaceHolderID="TitleContent" Runat="Server">
    Events
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="HeadContent" Runat="Server">
    <style>
        .events-hero {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%), url('../Images/events-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
            border-radius: 15px;
        }
        
        .search-filters {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }
        
        .event-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .event-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: grid;
            grid-template-columns: 300px 1fr auto;
            min-height: 200px;
        }
        
        .event-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .event-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            position: relative;
        }
        
        .event-date-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255,255,255,0.95);
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-align: center;
            min-width: 60px;
        }
        
        .event-content {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .event-type {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 1rem;
            align-self: flex-start;
        }
        
        .event-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .event-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
            flex-grow: 1;
        }
        
        .event-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .event-actions {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            background: #f8f9fa;
            min-width: 200px;
        }
        
        .rsvp-count {
            text-align: center;
            color: #667eea;
            font-weight: 600;
        }
        
        .rsvp-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
        }
        
        .rsvp-going {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .rsvp-maybe {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .rsvp-not-going {
            background: #ffebee;
            color: #c62828;
        }
        
        .no-events {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        
        .no-events i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid #e0e0e0;
        }
        
        .pagination a:hover {
            background: #f0f0f0;
        }
        
        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .event-item {
                grid-template-columns: 1fr;
            }
            
            .event-image {
                height: 200px;
            }
            
            .event-actions {
                min-width: auto;
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
</asp:Content>

<asp:Content ID="Content3" ContentPlaceHolderID="MainContent" Runat="Server">
    <!-- Events Hero -->
    <div class="events-hero">
        <div class="container">
            <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem;">
                <i class="fas fa-calendar-alt"></i> Alumni Events
            </h1>
            <p style="font-size: 1.2rem; opacity: 0.9;">
                Discover exciting opportunities to connect, learn, and grow with fellow alumni
            </p>
        </div>
    </div>

    <div class="container">
        <!-- Search and Filters -->
        <div class="search-filters">
            <asp:UpdatePanel ID="upFilters" runat="server">
                <ContentTemplate>
                    <div class="filter-grid">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Search Events</label>
                            <asp:TextBox ID="txtSearch" runat="server" CssClass="form-control" 
                                placeholder="Search by title, description, or location..." 
                                AutoPostBack="true" OnTextChanged="ApplyFilters"></asp:TextBox>
                        </div>
                        
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Event Type</label>
                            <asp:DropDownList ID="ddlEventType" runat="server" CssClass="form-control"
                                AutoPostBack="true" OnSelectedIndexChanged="ApplyFilters">
                                <asp:ListItem Value="" Text="All Types"></asp:ListItem>
                                <asp:ListItem Value="Reunion" Text="Reunion"></asp:ListItem>
                                <asp:ListItem Value="Workshop" Text="Workshop"></asp:ListItem>
                                <asp:ListItem Value="Networking" Text="Networking"></asp:ListItem>
                                <asp:ListItem Value="Social" Text="Social"></asp:ListItem>
                                <asp:ListItem Value="Fundraising" Text="Fundraising"></asp:ListItem>
                            </asp:DropDownList>
                        </div>
                        
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Time Period</label>
                            <asp:DropDownList ID="ddlTimePeriod" runat="server" CssClass="form-control"
                                AutoPostBack="true" OnSelectedIndexChanged="ApplyFilters">
                                <asp:ListItem Value="all" Text="All Events"></asp:ListItem>
                                <asp:ListItem Value="upcoming" Text="Upcoming" Selected="True"></asp:ListItem>
                                <asp:ListItem Value="this_month" Text="This Month"></asp:ListItem>
                                <asp:ListItem Value="next_month" Text="Next Month"></asp:ListItem>
                                <asp:ListItem Value="past" Text="Past Events"></asp:ListItem>
                            </asp:DropDownList>
                        </div>
                        
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Sort By</label>
                            <asp:DropDownList ID="ddlSortBy" runat="server" CssClass="form-control"
                                AutoPostBack="true" OnSelectedIndexChanged="ApplyFilters">
                                <asp:ListItem Value="date_asc" Text="Date (Newest First)" Selected="True"></asp:ListItem>
                                <asp:ListItem Value="date_desc" Text="Date (Oldest First)"></asp:ListItem>
                                <asp:ListItem Value="title_asc" Text="Title (A-Z)"></asp:ListItem>
                                <asp:ListItem Value="title_desc" Text="Title (Z-A)"></asp:ListItem>
                                <asp:ListItem Value="rsvp_desc" Text="Most Popular"></asp:ListItem>
                            </asp:DropDownList>
                        </div>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <asp:Button ID="btnClearFilters" runat="server" Text="Clear" 
                                CssClass="btn btn-secondary" OnClick="btnClearFilters_Click" />
                        </div>
                    </div>
                </ContentTemplate>
            </asp:UpdatePanel>
        </div>

        <!-- Events List -->
        <asp:UpdatePanel ID="upEvents" runat="server">
            <ContentTemplate>
                <div class="event-grid">
                    <asp:Repeater ID="rptEvents" runat="server" OnItemCommand="rptEvents_ItemCommand">
                        <ItemTemplate>
                            <div class="event-item fade-in">
                                <!-- Event Image -->
                                <div class="event-image">
                                    <div class="event-date-badge">
                                        <%# DateTime.Parse(Eval("EventDate").ToString()).ToString("MMM") %><br>
                                        <%# DateTime.Parse(Eval("EventDate").ToString()).ToString("dd") %>
                                    </div>
                                    <i class="fas fa-calendar-star"></i>
                                </div>
                                
                                <!-- Event Content -->
                                <div class="event-content">
                                    <div>
                                        <div class="event-type"><%# Eval("EventType") %></div>
                                        <h3 class="event-title"><%# Eval("Title") %></h3>
                                        <p class="event-description"><%# TruncateText(Eval("Description").ToString(), 150) %></p>
                                    </div>
                                    
                                    <div class="event-meta">
                                        <div class="event-meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><%# DateTime.Parse(Eval("EventDate").ToString()).ToString("dddd, MMMM dd, yyyy 'at' h:mm tt") %></span>
                                        </div>
                                        <div class="event-meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><%# Eval("Location") %></span>
                                        </div>
                                        <div class="event-meta-item">
                                            <i class="fas fa-user"></i>
                                            <span>Organized by <%# Eval("CreatedByName") ?? "Alumni Association" %></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Event Actions -->
                                <div class="event-actions">
                                    <div class="rsvp-count">
                                        <i class="fas fa-users"></i><br>
                                        <%# Eval("RSVP_Count") %> attending
                                    </div>
                                    
                                    <asp:Panel ID="pnlRSVPStatus" runat="server" Visible='<%# IsUserLoggedIn() %>'>
                                        <div class="rsvp-status rsvp-going">
                                            <i class="fas fa-check-circle"></i> Going
                                        </div>
                                    </asp:Panel>
                                    
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                                        <a href='EventDetails.aspx?id=<%# Eval("EventID") %>' class="btn" style="text-align: center;">
                                            <i class="fas fa-info-circle"></i> Details
                                        </a>
                                        
                                        <asp:Panel ID="pnlGuestActions" runat="server" Visible='<%# !IsUserLoggedIn() %>'>
                                            <a href="../Login.aspx" class="btn btn-secondary" style="text-align: center;">
                                                <i class="fas fa-sign-in-alt"></i> Login to RSVP
                                            </a>
                                        </asp:Panel>
                                        
                                        <asp:Panel ID="pnlUserActions" runat="server" Visible='<%# IsUserLoggedIn() %>'>
                                            <asp:Button ID="btnRSVP" runat="server" 
                                                CommandName="RSVP" 
                                                CommandArgument='<%# Eval("EventID") %>'
                                                Text="RSVP Now" 
                                                CssClass="btn btn-success" 
                                                style="width: 100%;" />
                                        </asp:Panel>
                                    </div>
                                </div>
                            </div>
                        </ItemTemplate>
                    </asp:Repeater>
                </div>
                
                <!-- No Events Message -->
                <asp:Panel ID="pnlNoEvents" runat="server" Visible="false">
                    <div class="no-events">
                        <i class="fas fa-calendar-times"></i>
                        <h3>No Events Found</h3>
                        <p>No events match your current search criteria. Try adjusting your filters or check back later for new events.</p>
                        <asp:Button ID="btnClearSearch" runat="server" Text="Clear Search" 
                            CssClass="btn" OnClick="btnClearFilters_Click" />
                    </div>
                </asp:Panel>
                
                <!-- Pagination -->
                <asp:Panel ID="pnlPagination" runat="server" CssClass="pagination">
                    <!-- Pagination controls will be added programmatically -->
                </asp:Panel>
            </ContentTemplate>
            <Triggers>
                <asp:AsyncPostBackTrigger ControlID="txtSearch" EventName="TextChanged" />
                <asp:AsyncPostBackTrigger ControlID="ddlEventType" EventName="SelectedIndexChanged" />
                <asp:AsyncPostBackTrigger ControlID="ddlTimePeriod" EventName="SelectedIndexChanged" />
                <asp:AsyncPostBackTrigger ControlID="ddlSortBy" EventName="SelectedIndexChanged" />
            </Triggers>
        </asp:UpdatePanel>

        <!-- Call to Action for Creating Events -->
        <asp:Panel ID="pnlCreateEvent" runat="server" Visible="false" CssClass="card" style="margin-top: 3rem; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body" style="padding: 3rem;">
                <h3><i class="fas fa-plus-circle"></i> Have an Event Idea?</h3>
                <p style="font-size: 1.1rem; margin: 1rem 0;">
                    Share your event ideas with the community and help bring alumni together!
                </p>
                <a href="../Contact.aspx" class="btn" style="background: white; color: #667eea;">
                    <i class="fas fa-lightbulb"></i> Suggest an Event
                </a>
            </div>
        </asp:Panel>
    </div>
</asp:Content>

<asp:Content ID="Content4" ContentPlaceHolderID="ScriptContent" Runat="Server">
    <script>
        $(document).ready(function() {
            // Add smooth animations to event cards
            $('.event-item').each(function(index) {
                $(this).delay(index * 100).queue(function() {
                    $(this).addClass('fade-in').dequeue();
                });
            });
            
            // Auto-refresh events every 5 minutes
            setInterval(function() {
                __doPostBack('<%= upEvents.UniqueID %>', '');
            }, 300000);
            
            // Search input enhancements
            $('#<%= txtSearch.ClientID %>').on('input', function() {
                // Add search icon animation or loading indicator
                var $this = $(this);
                if ($this.val().length > 0) {
                    $this.css('background-image', 'url("data:image/svg+xml;charset=utf8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' fill=\'%23667eea\' viewBox=\'0 0 16 16\'%3E%3Cpath d=\'M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z\'/%3E%3C/svg%3E")');
                    $this.css('background-repeat', 'no-repeat');
                    $this.css('background-position', 'right 10px center');
                    $this.css('padding-right', '35px');
                } else {
                    $this.css('background-image', 'none');
                    $this.css('padding-right', '0.75rem');
                }
            });
        });
        
        // RSVP confirmation
        function confirmRSVP(eventTitle) {
            return confirm('Are you sure you want to RSVP for "' + eventTitle + '"?');
        }
        
        // Share event functionality
        function shareEvent(eventId, eventTitle) {
            if (navigator.share) {
                navigator.share({
                    title: eventTitle,
                    text: 'Check out this alumni event: ' + eventTitle,
                    url: window.location.origin + '/Events/EventDetails.aspx?id=' + eventId
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                var url = window.location.origin + '/Events/EventDetails.aspx?id=' + eventId;
                navigator.clipboard.writeText(url).then(function() {
                    alert('Event link copied to clipboard!');
                });
            }
        }
    </script>
</asp:Content>