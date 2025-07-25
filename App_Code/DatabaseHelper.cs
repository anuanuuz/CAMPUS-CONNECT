using System;
using System.Data;
using System.Data.SqlClient;
using System.Configuration;
using System.Web;
using System.Linq;

/// <summary>
/// Database Helper class for Alumni Association Portal
/// Handles all database operations and connections
/// </summary>
public static class DatabaseHelper
{
    private static readonly string connectionString = ConfigurationManager.ConnectionStrings["AlumniConnectionString"].ConnectionString;

    #region Connection Management
    
    public static SqlConnection GetConnection()
    {
        return new SqlConnection(connectionString);
    }

    public static bool TestConnection()
    {
        try
        {
            using (SqlConnection conn = GetConnection())
            {
                conn.Open();
                return true;
            }
        }
        catch
        {
            return false;
        }
    }

    #endregion

    #region Execute Methods

    public static int ExecuteNonQuery(string query, params SqlParameter[] parameters)
    {
        try
        {
            using (SqlConnection conn = GetConnection())
            {
                conn.Open();
                using (SqlCommand cmd = new SqlCommand(query, conn))
                {
                    if (parameters != null)
                        cmd.Parameters.AddRange(parameters);
                    
                    return cmd.ExecuteNonQuery();
                }
            }
        }
        catch (Exception ex)
        {
            LogError("ExecuteNonQuery", ex);
            throw;
        }
    }

    public static object ExecuteScalar(string query, params SqlParameter[] parameters)
    {
        try
        {
            using (SqlConnection conn = GetConnection())
            {
                conn.Open();
                using (SqlCommand cmd = new SqlCommand(query, conn))
                {
                    if (parameters != null)
                        cmd.Parameters.AddRange(parameters);
                    
                    return cmd.ExecuteScalar();
                }
            }
        }
        catch (Exception ex)
        {
            LogError("ExecuteScalar", ex);
            throw;
        }
    }

    public static DataTable ExecuteDataTable(string query, params SqlParameter[] parameters)
    {
        try
        {
            using (SqlConnection conn = GetConnection())
            {
                conn.Open();
                using (SqlCommand cmd = new SqlCommand(query, conn))
                {
                    if (parameters != null)
                        cmd.Parameters.AddRange(parameters);
                    
                    using (SqlDataAdapter adapter = new SqlDataAdapter(cmd))
                    {
                        DataTable dt = new DataTable();
                        adapter.Fill(dt);
                        return dt;
                    }
                }
            }
        }
        catch (Exception ex)
        {
            LogError("ExecuteDataTable", ex);
            throw;
        }
    }

    public static DataSet ExecuteDataSet(string query, params SqlParameter[] parameters)
    {
        try
        {
            using (SqlConnection conn = GetConnection())
            {
                conn.Open();
                using (SqlCommand cmd = new SqlCommand(query, conn))
                {
                    if (parameters != null)
                        cmd.Parameters.AddRange(parameters);
                    
                    using (SqlDataAdapter adapter = new SqlDataAdapter(cmd))
                    {
                        DataSet ds = new DataSet();
                        adapter.Fill(ds);
                        return ds;
                    }
                }
            }
        }
        catch (Exception ex)
        {
            LogError("ExecuteDataSet", ex);
            throw;
        }
    }

    #endregion

    #region User Authentication Methods

    public static bool ValidateUser(string username, string password)
    {
        try
        {
            string hashedPassword = HashPassword(password);
            string query = @"SELECT COUNT(*) FROM Users 
                           WHERE (Username = @username OR Email = @username) 
                           AND Password = @password AND IsActive = 1";
            
            SqlParameter[] parameters = {
                new SqlParameter("@username", username),
                new SqlParameter("@password", hashedPassword)
            };

            int count = Convert.ToInt32(ExecuteScalar(query, parameters));
            return count > 0;
        }
        catch (Exception ex)
        {
            LogError("ValidateUser", ex);
            return false;
        }
    }

    public static string GetUserRole(string username)
    {
        try
        {
            string query = @"SELECT r.RoleName FROM Users u 
                           INNER JOIN Roles r ON u.RoleID = r.RoleID 
                           WHERE (u.Username = @username OR u.Email = @username) AND u.IsActive = 1";
            
            SqlParameter[] parameters = {
                new SqlParameter("@username", username)
            };

            object result = ExecuteScalar(query, parameters);
            return result?.ToString() ?? "Alumni";
        }
        catch (Exception ex)
        {
            LogError("GetUserRole", ex);
            return "Alumni";
        }
    }

    public static int GetUserID(string username)
    {
        try
        {
            string query = @"SELECT UserID FROM Users 
                           WHERE (Username = @username OR Email = @username) AND IsActive = 1";
            
            SqlParameter[] parameters = {
                new SqlParameter("@username", username)
            };

            object result = ExecuteScalar(query, parameters);
            return result != null ? Convert.ToInt32(result) : 0;
        }
        catch (Exception ex)
        {
            LogError("GetUserID", ex);
            return 0;
        }
    }

    #endregion

    #region User Management

    public static bool RegisterUser(string username, string email, string password, string firstName, 
                                  string lastName, string graduationYear, string department, string phone = "")
    {
        try
        {
            // Check if user already exists
            if (UserExists(username, email))
                return false;

            string hashedPassword = HashPassword(password);
            string query = @"INSERT INTO Users (Username, Email, Password, FirstName, LastName, 
                           GraduationYear, Department, Phone, RoleID, IsActive, CreatedDate)
                           VALUES (@username, @email, @password, @firstName, @lastName, 
                           @graduationYear, @department, @phone, 2, 1, GETDATE())";
            
            SqlParameter[] parameters = {
                new SqlParameter("@username", username),
                new SqlParameter("@email", email),
                new SqlParameter("@password", hashedPassword),
                new SqlParameter("@firstName", firstName),
                new SqlParameter("@lastName", lastName),
                new SqlParameter("@graduationYear", graduationYear),
                new SqlParameter("@department", department),
                new SqlParameter("@phone", phone ?? "")
            };

            int result = ExecuteNonQuery(query, parameters);
            return result > 0;
        }
        catch (Exception ex)
        {
            LogError("RegisterUser", ex);
            return false;
        }
    }

    public static bool UserExists(string username, string email)
    {
        try
        {
            string query = @"SELECT COUNT(*) FROM Users 
                           WHERE Username = @username OR Email = @email";
            
            SqlParameter[] parameters = {
                new SqlParameter("@username", username),
                new SqlParameter("@email", email)
            };

            int count = Convert.ToInt32(ExecuteScalar(query, parameters));
            return count > 0;
        }
        catch (Exception ex)
        {
            LogError("UserExists", ex);
            return false;
        }
    }

    #endregion

    #region Events Management

    public static DataTable GetUpcomingEvents(int count = 5)
    {
        try
        {
            string query = @"SELECT TOP (@count) EventID, Title, Description, EventDate, 
                           Location, ImageURL, CreatedBy, RSVP_Count
                           FROM Events 
                           WHERE EventDate >= GETDATE() AND IsActive = 1
                           ORDER BY EventDate ASC";
            
            SqlParameter[] parameters = {
                new SqlParameter("@count", count)
            };

            return ExecuteDataTable(query, parameters);
        }
        catch (Exception ex)
        {
            LogError("GetUpcomingEvents", ex);
            return new DataTable();
        }
    }

    public static DataTable GetAllEvents()
    {
        try
        {
            string query = @"SELECT e.EventID, e.Title, e.Description, e.EventDate, 
                           e.Location, e.ImageURL, e.RSVP_Count, e.IsActive,
                           u.FirstName + ' ' + u.LastName as CreatedByName
                           FROM Events e
                           LEFT JOIN Users u ON e.CreatedBy = u.UserID
                           WHERE e.IsActive = 1
                           ORDER BY e.EventDate DESC";

            return ExecuteDataTable(query);
        }
        catch (Exception ex)
        {
            LogError("GetAllEvents", ex);
            return new DataTable();
        }
    }

    #endregion

    #region Statistics

    public static int GetTotalAlumniCount()
    {
        try
        {
            string query = "SELECT COUNT(*) FROM Users WHERE IsActive = 1 AND RoleID = 2";
            return Convert.ToInt32(ExecuteScalar(query));
        }
        catch (Exception ex)
        {
            LogError("GetTotalAlumniCount", ex);
            return 0;
        }
    }

    public static int GetTotalEventsCount()
    {
        try
        {
            string query = "SELECT COUNT(*) FROM Events WHERE IsActive = 1";
            return Convert.ToInt32(ExecuteScalar(query));
        }
        catch (Exception ex)
        {
            LogError("GetTotalEventsCount", ex);
            return 0;
        }
    }

    public static int GetUpcomingEventsCount()
    {
        try
        {
            string query = "SELECT COUNT(*) FROM Events WHERE IsActive = 1 AND EventDate >= GETDATE()";
            return Convert.ToInt32(ExecuteScalar(query));
        }
        catch (Exception ex)
        {
            LogError("GetUpcomingEventsCount", ex);
            return 0;
        }
    }

    #endregion

    #region Utility Methods

    public static string HashPassword(string password)
    {
        // Simple hash implementation - in production, use BCrypt or similar
        using (System.Security.Cryptography.SHA256 sha256Hash = System.Security.Cryptography.SHA256.Create())
        {
            byte[] bytes = sha256Hash.ComputeHash(System.Text.Encoding.UTF8.GetBytes(password + "AlumniSalt2024"));
            return Convert.ToBase64String(bytes);
        }
    }

    public static void LogError(string method, Exception ex)
    {
        try
        {
            string query = @"INSERT INTO ErrorLogs (Method, ErrorMessage, StackTrace, CreatedDate)
                           VALUES (@method, @errorMessage, @stackTrace, GETDATE())";
            
            SqlParameter[] parameters = {
                new SqlParameter("@method", method),
                new SqlParameter("@errorMessage", ex.Message),
                new SqlParameter("@stackTrace", ex.StackTrace ?? "")
            };

            ExecuteNonQuery(query, parameters);
        }
        catch
        {
            // If logging fails, we don't want to throw another exception
        }
    }

    public static string GenerateRandomPassword(int length = 8)
    {
        const string chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%";
        Random random = new Random();
        return new string(Enumerable.Repeat(chars, length)
            .Select(s => s[random.Next(s.Length)]).ToArray());
    }

    #endregion
}