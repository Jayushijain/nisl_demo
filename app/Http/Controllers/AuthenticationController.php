<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Facades\App\Authentication;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return (new AuthenticationController())->login($request);
    }

    public function login(Request $request)
    {
        if ($request->email)
        {
            $email    = $request->email;
            $password = $request->password;
            $remember = $request->remember;         
            $user = Authentication::login($email, $password, $remember);
            
            if (is_array($user) && isset($user['user_inactive']))
            {
                set_alert('error', __('messages.your_account_is_not_active'));
                return redirect('/authentication');
            }
            elseif (is_array($user) && isset($user['invalid_email']))
            {
                set_alert('error', __('messages.incorrect_email'));
                return redirect('/authentication');
            }
            elseif (is_array($user) && isset($user['email_unverified']))
            {
                set_alert('error', 'Your email is not verified. Please verify your email first.');
                return redirect('/authentication');
            }
            elseif (is_array($user) && isset($user['invalid_password']))
            {
                set_alert('error', __('messages.incorrect_password'));
                return redirect('/authentication');
            }
            elseif ($user == false)
            {
                set_alert('error', __('messages.incorrect_email_or_password'));
                return redirect('/authentication');
            }

            //If previous redirect URL is set in session, redirect to that URL
            //maybe_redirect_to_previous_url();

            //Else rediret to admin home page.
            //return 'true';
            return redirect('/');
        }
        else
        {
            echo view('themes.default.authentication.login_signup');
        }
    }

    public function signup(Request $request)
    {
        if ($request->password)
        {
            $input =$request->except(['_token']);

            $input['password'] = md5($input['password']);
            unset($input['confirm_password']);

            $input['signup_key'] = app_generate_hash();

            if (User::insert($input))
            {
                $template = get_email_template('new-user-signup');
                $subject  = str_replace('{company_name}', get_settings('company_name'), $template->subject);

                $message = get_settings('email_header');

                $find = [
                    '{firstname}',
                    '{lastname}',
                    '{email_verification_url}',
                    '{email_signature}',
                    '{company_name}'
                ];

                $replace = [
                    $data['firstname'],
                    $data['lastname'],
                    url('/authentication/verify_email/').$data['signup_key'],
                    get_settings('email_signature'),
                    get_settings('company_name')
                ];

                $message .= str_replace($find, $replace, $template['message']);

                $message .= str_replace('{company_name}', get_settings('company_name'), get_settings('email_footer'));

                //mail helper
                $sent = send_email($data['email'], $subject, $message);

                if ($sent)
                {
                    set_alert('success', 'Your are registered successfully. Please check your email for account verification instructions.');
                    return redirect('/authentication');
                }
            }
        }

        return view('themes.default.authentication.login_signup');
    }

    public function verify_email($signup_key = '')
    {
        if ($signup_key == '')
        {
            return redirect('/');
        }

        $success = Authentication::verify_email($signup_key);

        if ($success == true)
        {
            set_alert('success', 'Your Email is verified. You can login now.');
        }
        else
        {
            set_alert('error', 'Some issue in verifying your email.');
        }

        return redirect('/authentication');
    }

    /**
     * Loads forgot password form & performs forgot password
     */
    public function forgot_password(Request $request)
    {
        //$this->set_page_title(_l('forgot_password'));

        if (is_user_logged_in())
        {
            return redirect('/');
        }

        if ($request->email)
        {
           $success = Authentication::forgot_password($request->email, true);

            if (is_array($success) && isset($success['user_inactive']))
            {
                set_alert('error', __('messages.your_account_is_not_active'));
            }
            elseif (is_array($success) && isset($success['email_unverified']))
            {
                set_alert('error', 'Your email is not verified. Please verify your email first.');
            }
            elseif (is_array($success) && isset($success['invalid_user']))
            {
                set_alert('error', __('messages.incorrect_email'));
            }
            elseif ($success == true)
            {
                set_alert('success', __('messages.check_email_for_resetting_password'));
            }
            else
            {
                set_alert('error', __('messages.error_setting_new_password_key'));
            }

            return redirect('/authentication/forgot_password');
        }

        return view('themes.default.authentication.forgot_password');
    }

    /**
     * Loads reset password form & resets the password
     *
     * @param int  $user_id       The user identifier
     * @param str  $new_pass_key  The new pass key
     */
    public function reset_password(Request $request,$user_id = 0, $new_pass_key = '')
    {
        if (($user_id == 0) || ($new_pass_key == ''))
        {
            return redirect('/');
        }

        //$this->set_page_title(_l('reset_password'));

        if (!Authentication::can_reset_password($user_id, $new_pass_key))
        {
            set_alert('error', __('messages.password_reset_key_expired'));
            return redirect('/authentication');
        }

        if ($request->password)
        {
            $success = $this->Authentication_model->reset_password($user_id, $new_pass_key, $this->input->post('password'));

            if (is_array($success) && $success['expired'] == true)
            {
                set_alert('error', _l('password_reset_key_expired'));
            }
            elseif ($success == true)
            {
                set_alert('success', _l('password_reset_message'));
                log_activity('User Resetted the Password', $user_id);
            }
            else
            {
                set_alert('error', _l('new_password_is_same_as_old_password'));
                redirect(site_url($this->uri->uri_string()));
            }

            redirect(site_url('authentication'));
        }

        $this->template->load('index', 'content', 'authentication/reset_password');
    }

    /**
     * Checks if user with provided email id exists or not
     */
    // public function email_exists(Request $request)
    // {
    //     $exists = User::where('email', $request->email)->count();

    //     echo $exists;
    // }

    /**
     * Does logout
     */
    public function logout()
    {
        Authentication::logout();
        return redirect('/authentication');
    }

}
