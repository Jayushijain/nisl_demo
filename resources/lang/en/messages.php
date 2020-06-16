<?php

return [

#=============================================
#Names: labels, place holders, buttons, events
#=============================================
#General
#-------
'welcome'         => 'Welcome',
'add'             => 'Add',
'add_new'         => 'Add New',
'view'            => 'View',
'create'          => 'Create',
'edit'            => 'Edit',
'delete'          => 'Delete',
'delete_selected' => 'Delete Selected',
'search'          => 'Search',
'id'              => 'ID',
'name'            => 'Name',
'status'          => 'Status',
'active'          => 'Active',
'inactive'        => 'Inactive',
'description'     => 'Description',
'created_at'      => 'Created at',
'actions'         => 'Actions',
'confirm'         => 'Confirm',
'save'            => 'Save',
'back'            => 'Back',
'close'           => 'Close',
'no_cancel_it'    => 'No, Cancel It',
'yes_i_am_sure'   => 'Yes, I am sure',
'click_to_sort'   => 'Click to sort',

#Login/Logout/Forgot Password/Change Password/Profile
#----------------------------------------------------
'email'            => 'Email',
'password'         => 'Password',
'old_password'     => 'Old Password',
'new_password'     => 'New Password',
'confirm_password' => 'Confirm Password',
'repeat_password'  => 'Repeat Password',
'reset_password'   => 'Reset Password',
'change_password'  => 'Change Password',
'remember_me'      => 'Remember Me',
'forgot_password'  => 'Forgot Password?',
'profile'          => 'Profile',
'edit_profile'     => 'Edit Profile',
'login'            => 'Log In',
'logout'           => 'Log Out',

#Dashboard
#---------
'dashboard' => 'Dashboard',

#Users
#-----
'firstname'  => 'Firstname',
'lastname'   => 'Lastname',
'contact_no' => 'Contact No',
'mobile_no'  => 'Mobile No',
'last_login' => 'Last Login',
'user'       => 'User',
'users'      => 'Users',
'add_user'   => 'Add User',
'edit_user'  => 'Edit User',

#Projects
#--------
'project'      => 'Project',
'projects'     => 'Projects',
'project_name' => 'Project Name',
'add_project'  => 'Add Project',
'edit_project' => 'Edit Project',

#Categories
#----------
'category'      => 'Category',
'categories'    => 'Categories',
'category_name' => 'Category Name',
'add_category'  => 'Add Category',
'edit_category' => 'Edit Category',

#Roles
#-----
'role'         => 'Role',
'roles'        => 'Roles',
'role_name'    => 'Role Name',
'add_role'     => 'Add Role',
'edit_role'    => 'Edit Role',
'features'     => 'Features',
'capabilities' => 'Capabilities',

#Settings
#--------
'settings'      => 'Settings',
'general'       => 'General',
'company_info'  => 'Company Information',
'company_name'  => 'Company Name',
'company_email' => 'Company Email',
'date_time'     => 'Date & Time',
'social_media'  => 'Social Media',
'log_activity'  => 'Log Activity',

#Time related
#------------
'never'   => 'Never',
'second'  => 'second',
'seconds' => 'seconds',
'minute'  => 'minute',
'minutes' => 'minutes',
'hour'    => 'hour',
'hours'   => 'hours',
'day'     => 'day',
'days'    => 'days',
'week'    => 'week',
'weeks'   => 'weeks',
'month'   => 'month',
'months'  => 'months',
'year'    => 'year',
'years'   => 'years',

#=============================================
#Instructions, messages, alerts, notifications
#=============================================
#General
#-------
'no_data_found'              => 'No Data Found.',
'_added_successfully'        => ':Name added successfully.',
'_updated_successfully'      => ':Name updated successfully.',
'_deleted_successfully'      => ':Name deleted successfully.',
'_activated'                 => ':Name activated.',
'_deactivated'               => ':Name deactivated.',
'please_enter_'              => 'Please Enter  :Name',
'please_enter_valid_'        => 'Please Enter Valid :Name',
'please_select_'             => 'Please Select :Name',
'single_deletion_alert'      => 'Are you sure you want to delete this record?',
'single_recovery_alert'      => 'You will not be able to recover this record after deletion.',
'multiple_deletion_alert'    => 'Are you sure you want to delete selected records?',
'multiple_recovery_alert'    => 'You will not be able to recover these records after deletion.',
'select_before_delete_alert' => 'Please select some records to delete.',
'access_denied'              => 'You do not have enough permissions to access this page. Please contact to your Administrator.',

#Login/Logout/Forgot Password/Change Password/Profile
#----------------------------------------------------
'login_to_your_account'                                  => 'Login to your account',
'enter_your_credentials_below'                           => 'Please enter your credentials below',
'incorrect_email'                                        => 'Incorrect email.',
'incorrect_password'                                     => 'Incorrect password.',
'email_exists'                                     => 'This Email has already been taken.',

'incorrect_email_or_password'                            => 'Email and/or password is incorrect.',
'your_account_is_not_active'                             => 'Your account is not active. Please contact to your Administrator.',
'forgot_password_instructions'                           => 'Please enter your email address below. <br/>We will send you instructions in email to reset your password.',
'check_email_for_resetting_password'                     => 'Check your email for further instructions for resetting your password.',
'error_setting_new_password_key'                         => 'Error setting new password.',
'password_reset_key_expired'                             => 'Reset Password key expired.',
'password_reset_message'                                 => 'Your password has been reset. You can login now!',
'new_password_is_same_as_old_password'                   => 'The new password you are trying to set is the same as your current password. You can use the same to login. Or you may change it to a different password.',
'password_min_length_must_be_'                           => 'Password length must be minimum :Name characters.',
'conf_password_donot_match'                              => 'Confirm password does not match with password.',
'enter_new_password_only_if_you_want_to_change_password' => 'Enter new password only if you want to change password. Keep it blank otherwise.',
'last_password_change_msg'                               => 'Your password was lastly changed <b>:Name</b> ago.',

#Role Permissions
#----------------
'please_select_some_role_permissions' => 'Plase select some role permissions.',
'users_using_role_msg'                => 'Users which are currently using the role',
'edit_role_warning_alert'             => 'Changing role permissions will affect current users permissions that are using this role.',
'role_in_use_deletion_alert'          => 'The role you are trying to delete is currently assigned to one or more users.',
'single_role_not_deleted_msg'         => "<p>The role <span style=>'color: red,'>(:Name)</span> was not deleted because it is assigned to one or more users.</p>",
'multiple_roles_not_deleted_msg'      => "<p>The roles <span style=>'color: red,'>(:Name)</span> were not deleted because they are assigned to one or more users.</p>",
'single_role_deleted_msg'             => "<p>The role <span style=>'color: green,'>(:Name)</span> was deleted successfully.</p>",
'multiple_roles_deleted_msg'          => "<p>The roles <span style=>'color: green,'>(:Name)</span> were deleted successfully.</p>"
];