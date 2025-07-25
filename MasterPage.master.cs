using System;
using System.Web;
using System.Web.UI;
using System.Web.Security;

public partial class MasterPage : System.Web.UI.MasterPage
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            UpdateNavigationVisibility();
        }
    }

    private void UpdateNavigationVisibility()
    {
        if (HttpContext.Current.User.Identity.IsAuthenticated)
        {
            // User is logged in
            pnlGuestMenu.Visible = false;
            pnlUserMenu.Visible = true;

            // Check if user is admin
            if (HttpContext.Current.User.IsInRole("Admin"))
            {
                pnlAdminMenu.Visible = true;
            }
            else
            {
                pnlAdminMenu.Visible = false;
            }
        }
        else
        {
            // User is not logged in
            pnlGuestMenu.Visible = true;
            pnlUserMenu.Visible = false;
            pnlAdminMenu.Visible = false;
        }
    }

    protected void lnkLogout_Click(object sender, EventArgs e)
    {
        try
        {
            // Clear the session
            Session.Clear();
            Session.Abandon();

            // Sign out using Forms Authentication
            FormsAuthentication.SignOut();

            // Clear authentication cookie
            HttpCookie authCookie = new HttpCookie(FormsAuthentication.FormsCookieName, "");
            authCookie.Expires = DateTime.Now.AddYears(-1);
            Response.Cookies.Add(authCookie);

            // Redirect to home page
            Response.Redirect("~/Default.aspx");
        }
        catch (Exception ex)
        {
            ShowMessage("An error occurred during logout: " + ex.Message, "error");
        }
    }

    public void ShowMessage(string message, string type = "info")
    {
        pnlMessages.Visible = true;
        lblMessage.Text = message;
        
        // Remove existing CSS classes
        lblMessage.CssClass = "message";
        
        // Add appropriate CSS class based on message type
        switch (type.ToLower())
        {
            case "success":
                lblMessage.CssClass += " message-success";
                break;
            case "error":
                lblMessage.CssClass += " message-error";
                break;
            case "info":
            default:
                lblMessage.CssClass += " message-info";
                break;
        }
    }

    public void HideMessage()
    {
        pnlMessages.Visible = false;
    }
}