using System;
using System.Data;
using System.Web;
using System.Web.UI;

public partial class _Default : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            LoadPageData();
            UpdateUserInterface();
        }
    }

    private void LoadPageData()
    {
        try
        {
            // Load statistics
            LoadStatistics();
            
            // Load upcoming events
            LoadUpcomingEvents();
        }
        catch (Exception ex)
        {
            // Log error and show user-friendly message
            DatabaseHelper.LogError("Default.Page_Load", ex);
            
            MasterPage master = this.Master as MasterPage;
            if (master != null)
            {
                master.ShowMessage("Welcome to Alumni Portal! Some content may be temporarily unavailable.", "info");
            }
        }
    }

    private void LoadStatistics()
    {
        try
        {
            // Get total alumni count
            int totalAlumni = DatabaseHelper.GetTotalAlumniCount();
            lblTotalAlumni.Text = totalAlumni.ToString("N0");

            // Get upcoming events count
            int upcomingEvents = DatabaseHelper.GetUpcomingEventsCount();
            lblUpcomingEvents.Text = upcomingEvents.ToString();
        }
        catch (Exception ex)
        {
            DatabaseHelper.LogError("LoadStatistics", ex);
            
            // Set default values if database is not available
            lblTotalAlumni.Text = "1,250+";
            lblUpcomingEvents.Text = "5";
        }
    }

    private void LoadUpcomingEvents()
    {
        try
        {
            DataTable dtEvents = DatabaseHelper.GetUpcomingEvents(4); // Get top 4 events
            
            if (dtEvents.Rows.Count > 0)
            {
                rptUpcomingEvents.DataSource = dtEvents;
                rptUpcomingEvents.DataBind();
                pnlNoEvents.Visible = false;
            }
            else
            {
                rptUpcomingEvents.DataSource = null;
                rptUpcomingEvents.DataBind();
                pnlNoEvents.Visible = true;
            }
        }
        catch (Exception ex)
        {
            DatabaseHelper.LogError("LoadUpcomingEvents", ex);
            
            // Show sample data if database is not available
            CreateSampleEvents();
        }
    }

    private void CreateSampleEvents()
    {
        try
        {
            // Create sample data for demonstration
            DataTable dtSample = new DataTable();
            dtSample.Columns.Add("EventID", typeof(int));
            dtSample.Columns.Add("Title", typeof(string));
            dtSample.Columns.Add("Description", typeof(string));
            dtSample.Columns.Add("EventDate", typeof(DateTime));
            dtSample.Columns.Add("Location", typeof(string));

            // Add sample events
            dtSample.Rows.Add(1, "Annual Alumni Reunion 2024", 
                "Join us for our biggest gathering of the year! Reconnect with classmates, enjoy great food, and celebrate our shared memories.", 
                DateTime.Now.AddDays(30), "University Campus, Main Hall");

            dtSample.Rows.Add(2, "Tech Career Workshop", 
                "Learn about the latest trends in technology and get career advice from successful alumni in the tech industry.", 
                DateTime.Now.AddDays(15), "Innovation Center, Room 205");

            dtSample.Rows.Add(3, "Networking Happy Hour", 
                "Casual networking event for alumni in the city. Great opportunity to make new connections over drinks and appetizers.", 
                DateTime.Now.AddDays(7), "Downtown Plaza Hotel");

            dtSample.Rows.Add(4, "Scholarship Fundraising Gala", 
                "Help us raise funds for student scholarships while enjoying an elegant evening of dinner, dancing, and entertainment.", 
                DateTime.Now.AddDays(45), "Grand Ballroom, City Center");

            rptUpcomingEvents.DataSource = dtSample;
            rptUpcomingEvents.DataBind();
            pnlNoEvents.Visible = false;
        }
        catch (Exception ex)
        {
            DatabaseHelper.LogError("CreateSampleEvents", ex);
            pnlNoEvents.Visible = true;
        }
    }

    private void UpdateUserInterface()
    {
        if (HttpContext.Current.User.Identity.IsAuthenticated)
        {
            // User is logged in
            pnlGuestActions.Visible = false;
            pnlUserActions.Visible = true;
            pnlCTAGuest.Visible = false;
            pnlCTAUser.Visible = true;

            // Welcome message for logged in users
            MasterPage master = this.Master as MasterPage;
            if (master != null)
            {
                string welcomeMessage = $"Welcome back! Great to see you again.";
                master.ShowMessage(welcomeMessage, "success");
            }
        }
        else
        {
            // User is not logged in
            pnlGuestActions.Visible = true;
            pnlUserActions.Visible = false;
            pnlCTAGuest.Visible = true;
            pnlCTAUser.Visible = false;
        }
    }

    protected override void OnPreRender(EventArgs e)
    {
        base.OnPreRender(e);
        
        // Add smooth scroll behavior for better UX
        string script = @"
            $(document).ready(function() {
                // Animate statistics counters
                $('.widget-value').each(function() {
                    var $this = $(this);
                    var countTo = $this.text().replace(/[^0-9]/g, '');
                    if (countTo && !isNaN(countTo)) {
                        $({ countNum: 0 }).animate({
                            countNum: countTo
                        }, {
                            duration: 2000,
                            easing: 'swing',
                            step: function() {
                                $this.text(Math.floor(this.countNum).toLocaleString());
                            },
                            complete: function() {
                                $this.text(parseInt(countTo).toLocaleString());
                            }
                        });
                    }
                });

                // Add entrance animations
                $('.stat-card, .event-card, .feature-card').each(function(index) {
                    $(this).delay(index * 100).queue(function() {
                        $(this).addClass('fade-in').dequeue();
                    });
                });
            });
        ";

        ClientScript.RegisterStartupScript(this.GetType(), "HomePageAnimations", script, true);
    }
}