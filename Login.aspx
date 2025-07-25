<%@ Page Title="Login" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Login.aspx.cs" Inherits="Login" %>

<asp:Content ID="Content1" ContentPlaceHolderID="TitleContent" Runat="Server">
    Login
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="HeadContent" Runat="Server">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 2rem 0;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 500px;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        
        .login-right {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .login-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .welcome-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            opacity: 0.8;
        }
        
        .form-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .form-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .social-btn {
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .social-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            text-decoration: none;
            color: #667eea;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #999;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }
        
        .divider span {
            padding: 0 1rem;
            font-size: 0.9rem;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 1rem;
        }
        
        .forgot-password a {
            color: #667eea;
            text-decoration: none;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
                margin: 1rem;
            }
            
            .login-left {
                padding: 2rem;
            }
            
            .login-title {
                font-size: 2rem;
            }
            
            .welcome-icon {
                font-size: 3rem;
            }
        }
    </style>
</asp:Content>

<asp:Content ID="Content3" ContentPlaceHolderID="MainContent" Runat="Server">
    <div class="login-container">
        <div class="container">
            <div class="login-card fade-in">
                <!-- Left Side - Welcome Section -->
                <div class="login-left">
                    <div class="welcome-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1 class="login-title">Welcome Back!</h1>
                    <p class="login-subtitle">
                        Connect with your alumni network and discover new opportunities. 
                        Your journey continues here.
                    </p>
                    <div style="margin-top: 2rem;">
                        <h4><i class="fas fa-check-circle"></i> Stay Connected</h4>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Network with fellow graduates</p>
                        
                        <h4 style="margin-top: 1rem;"><i class="fas fa-calendar-alt"></i> Attend Events</h4>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Join exclusive alumni gatherings</p>
                        
                        <h4 style="margin-top: 1rem;"><i class="fas fa-rocket"></i> Grow Your Career</h4>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Access mentorship and job opportunities</p>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="login-right">
                    <h2 class="form-title">Sign In</h2>
                    <p class="form-subtitle">Enter your credentials to access your account</p>
                    
                    <!-- Social Login Buttons -->
                    <div class="social-login">
                        <a href="#" class="social-btn">
                            <i class="fab fa-google"></i> Google
                        </a>
                        <a href="#" class="social-btn">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    </div>
                    
                    <div class="divider">
                        <span>or continue with email</span>
                    </div>
                    
                    <!-- Login Form -->
                    <asp:UpdatePanel ID="upLogin" runat="server">
                        <ContentTemplate>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i> Username or Email
                                </label>
                                <asp:TextBox ID="txtUsername" runat="server" CssClass="form-control" 
                                    placeholder="Enter your username or email" MaxLength="100" 
                                    autocomplete="username"></asp:TextBox>
                                <asp:RequiredFieldValidator ID="rfvUsername" runat="server" 
                                    ControlToValidate="txtUsername" 
                                    ErrorMessage="Username or email is required" 
                                    CssClass="error-message" Display="Dynamic"></asp:RequiredFieldValidator>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Password
                                </label>
                                <asp:TextBox ID="txtPassword" runat="server" TextMode="Password" 
                                    CssClass="form-control" placeholder="Enter your password" 
                                    MaxLength="100" autocomplete="current-password"></asp:TextBox>
                                <asp:RequiredFieldValidator ID="rfvPassword" runat="server" 
                                    ControlToValidate="txtPassword" 
                                    ErrorMessage="Password is required" 
                                    CssClass="error-message" Display="Dynamic"></asp:RequiredFieldValidator>
                            </div>
                            
                            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                                    <asp:CheckBox ID="chkRememberMe" runat="server" />
                                    <span style="font-size: 0.9rem;">Remember me</span>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <asp:Button ID="btnLogin" runat="server" Text="Sign In" 
                                    CssClass="btn btn-large" OnClick="btnLogin_Click" 
                                    style="width: 100%;" />
                            </div>
                            
                            <div class="forgot-password">
                                <a href="ForgotPassword.aspx">
                                    <i class="fas fa-key"></i> Forgot your password?
                                </a>
                            </div>
                            
                            <div class="register-link">
                                <p>Don't have an account? 
                                    <a href="Register.aspx">
                                        <i class="fas fa-user-plus"></i> Create one here
                                    </a>
                                </p>
                            </div>
                        </ContentTemplate>
                    </asp:UpdatePanel>
                </div>
            </div>
        </div>
    </div>
</asp:Content>

<asp:Content ID="Content4" ContentPlaceHolderID="ScriptContent" Runat="Server">
    <script>
        $(document).ready(function() {
            // Focus on username field
            $('#<%= txtUsername.ClientID %>').focus();
            
            // Enter key handling
            $('#<%= txtUsername.ClientID %>, #<%= txtPassword.ClientID %>').keypress(function(e) {
                if (e.which === 13) { // Enter key
                    $('#<%= btnLogin.ClientID %>').click();
                }
            });
            
            // Form validation styling
            $('input').on('blur', function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            
            // Password visibility toggle
            $('<button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>')
                .insertAfter('#<%= txtPassword.ClientID %>')
                .css({
                    'position': 'absolute',
                    'right': '10px',
                    'top': '50%',
                    'transform': 'translateY(-50%)',
                    'border': 'none',
                    'background': 'transparent',
                    'color': '#667eea',
                    'cursor': 'pointer'
                })
                .on('click', function() {
                    var passwordField = $('#<%= txtPassword.ClientID %>');
                    var icon = $(this).find('i');
                    
                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            
            // Make password field container relative for toggle button
            $('#<%= txtPassword.ClientID %>').parent().css('position', 'relative');
        });
    </script>
</asp:Content>