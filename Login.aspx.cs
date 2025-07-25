using System;
using System.Web;
using System.Web.Security;
using System.Web.UI;

public partial class Login : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            // If user is already logged in, redirect to home page
            if (HttpContext.Current.User.Identity.IsAuthenticated)
            {
                Response.Redirect("~/Default.aspx");
            }

            // Check if there's a return URL
            string returnUrl = Request.QueryString["ReturnUrl"];
            if (!string.IsNullOrEmpty(returnUrl))
            {
                ViewState["ReturnUrl"] = returnUrl;
            }
        }
    }

    protected void btnLogin_Click(object sender, EventArgs e)
    {
        if (Page.IsValid)
        {
            try
            {
                string username = txtUsername.Text.Trim();
                string password = txtPassword.Text;
                bool rememberMe = chkRememberMe.Checked;

                // Validate credentials
                if (ValidateUser(username, password))
                {
                    // Get user role
                    string userRole = DatabaseHelper.GetUserRole(username);
                    
                    // Create authentication ticket
                    FormsAuthenticationTicket ticket = new FormsAuthenticationTicket(
                        1,                          // version
                        username,                   // username
                        DateTime.Now,              // issue time
                        DateTime.Now.AddMinutes(rememberMe ? 43200 : 60), // expiration (30 days if remember me, 1 hour otherwise)
                        rememberMe,                // persistent
                        userRole,                  // user data (role)
                        FormsAuthentication.FormsCookiePath
                    );

                    // Encrypt the ticket
                    string encryptedTicket = FormsAuthentication.Encrypt(ticket);

                    // Create cookie
                    HttpCookie authCookie = new HttpCookie(FormsAuthentication.FormsCookieName, encryptedTicket);
                    if (rememberMe)
                    {
                        authCookie.Expires = DateTime.Now.AddDays(30);
                    }
                    Response.Cookies.Add(authCookie);

                    // Store user information in session
                    Session["UserID"] = DatabaseHelper.GetUserID(username);
                    Session["Username"] = username;
                    Session["UserRole"] = userRole;

                    // Log successful login
                    LogLoginAttempt(username, true, Request.UserHostAddress);

                    // Redirect to appropriate page
                    string returnUrl = ViewState["ReturnUrl"] as string;
                    if (!string.IsNullOrEmpty(returnUrl))
                    {
                        Response.Redirect(returnUrl);
                    }
                    else
                    {
                        // Redirect based on user role
                        switch (userRole.ToLower())
                        {
                            case "admin":
                                Response.Redirect("~/Admin/Dashboard.aspx");
                                break;
                            case "alumni":
                            default:
                                Response.Redirect("~/Default.aspx");
                                break;
                        }
                    }
                }
                else
                {
                    // Invalid credentials
                    LogLoginAttempt(username, false, Request.UserHostAddress);
                    ShowError("Invalid username/email or password. Please try again.");
                }
            }
            catch (Exception ex)
            {
                DatabaseHelper.LogError("Login.btnLogin_Click", ex);
                ShowError("An error occurred during login. Please try again later.");
            }
        }
    }

    private bool ValidateUser(string username, string password)
    {
        try
        {
            return DatabaseHelper.ValidateUser(username, password);
        }
        catch (Exception ex)
        {
            DatabaseHelper.LogError("Login.ValidateUser", ex);
            return false;
        }
    }

    private void LogLoginAttempt(string username, bool successful, string ipAddress)
    {
        try
        {
            string query = @"INSERT INTO LoginLogs (Username, IsSuccessful, IPAddress, AttemptDate)
                           VALUES (@username, @successful, @ipAddress, GETDATE())";
            
            var parameters = new System.Data.SqlClient.SqlParameter[]
            {
                new System.Data.SqlClient.SqlParameter("@username", username),
                new System.Data.SqlClient.SqlParameter("@successful", successful),
                new System.Data.SqlClient.SqlParameter("@ipAddress", ipAddress)
            };

            DatabaseHelper.ExecuteNonQuery(query, parameters);
        }
        catch (Exception ex)
        {
            DatabaseHelper.LogError("Login.LogLoginAttempt", ex);
        }
    }

    private void ShowError(string message)
    {
        MasterPage master = this.Master as MasterPage;
        if (master != null)
        {
            master.ShowMessage(message, "error");
        }
    }

    protected override void OnPreRender(EventArgs e)
    {
        base.OnPreRender(e);
        
        // Add client-side validation enhancements
        string script = @"
            $(document).ready(function() {
                // Real-time validation
                $('#" + txtUsername.ClientID + @"').on('input', function() {
                    var value = $(this).val().trim();
                    if (value.length > 0) {
                        if (value.includes('@')) {
                            // Email validation
                            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (emailRegex.test(value)) {
                                $(this).removeClass('error');
                                $(this).next('.error-message').hide();
                            } else {
                                $(this).addClass('error');
                                $(this).next('.error-message').text('Please enter a valid email address').show();
                            }
                        } else {
                            // Username validation
                            if (value.length >= 3) {
                                $(this).removeClass('error');
                                $(this).next('.error-message').hide();
                            } else {
                                $(this).addClass('error');
                                $(this).next('.error-message').text('Username must be at least 3 characters').show();
                            }
                        }
                    }
                });

                $('#" + txtPassword.ClientID + @"').on('input', function() {
                    var value = $(this).val();
                    if (value.length >= 6) {
                        $(this).removeClass('error');
                        $(this).next('.error-message').hide();
                    } else if (value.length > 0) {
                        $(this).addClass('error');
                        $(this).next('.error-message').text('Password must be at least 6 characters').show();
                    }
                });

                // Loading state for login button
                $('#" + btnLogin.ClientID + @"').click(function() {
                    var $btn = $(this);
                    var originalText = $btn.val();
                    $btn.val('Signing In...').prop('disabled', true);
                    
                    setTimeout(function() {
                        $btn.val(originalText).prop('disabled', false);
                    }, 3000);
                });
            });
        ";

        ClientScript.RegisterStartupScript(this.GetType(), "LoginValidation", script, true);
    }
}