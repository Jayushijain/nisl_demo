<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class EmailController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try{
			$this->load_default_templates();
			$templates = EmailTemplate::get();
			$data['page_title'] = 'Email';
			return view('admin.emails.index', compact('templates'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('EmailController index: '.$e->getMessage());
            return redirect('/admin/dashboard')->with('error_message','Something went wrong! Please Try again');
        }         
	}

	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		try{
			$template = EmailTemplate::find($id);
			$data['page_title'] = 'Email';
			return view('admin.emails.email_template', compact('template'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('EmailController edit: '.$e->getMessage());
            return redirect()->route('settings.index')->with('error_message','Something went wrong! Please Try again');
        } 
		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		try{
			$template = EmailTemplate::find($id);
			$input    = $request->except(['_method', '_token']);		
			$update = $template->update($input);		

			if ($update)
			{
				set_alert('success', __('messages._updated_successfully', ['Name'=>'Email Template']));
				return redirect('/admin/email_templates');
			}
		}
		catch (\RuntimeException $e){
            Log::info('EmailController update: '.$e->getMessage());
            return redirect()->route('settings.index')->with('error_message','Something went wrong! Please Try again');
        } 
		
	}

    /**
     * Loads default templates data into the database if not already exists.
     */
    private function load_default_templates()
    {
    	try{
    		$templates = $this->default_templates();
	        foreach ($templates as $template)
	        {
	            $template_exists = EmailTemplate::where('slug',$template['slug'])->count();
	            if ($template['name'] != '' && $template['slug'] != '')
	            {
	                if ($template_exists == 0)
	                {
	                    $input = [
	                        'name'         => $template['name'],
	                        'slug'         => $template['slug'],
	                        'placeholders' => serialize($template['placeholders'])
	                    ];
	                    EmailTemplate::insert($input);
	                }
	                else
	                {
	                    $input = [
	                        'name'         => $template['name'],
	                        'placeholders' => serialize($template['placeholders'])
	                    ];
	                    $template = EmailTemplate::where('slug',$template['slug']);
	                    $template->update($input);
	                }
	            }
	        }
    	}
    	catch (\RuntimeException $e){
            Log::info('EmailController update: '.$e->getMessage());
            return redirect()->route('settings.index')->with('error_message','Something went wrong! Please Try again');
        }        
    }

    /**
     * Contains the Default Email Templates to be used in the system.
     * You can add or remove Templates in this function & it will reflect  * on the Email Templates Module
     *
     * @return [array]      The default email templates with their placeholders information
     */
    public function default_templates()
    {
    	try{
    		$templates = [
	            [
	                'name'         => 'Forgot Password',
	                'slug'         => 'forgot-password',
	                'placeholders' => [
	                    '{firstname}'          => 'User Firstname',
	                    '{lastname}'           => 'User Lastname',
	                    '{email}'              => 'User Email',
	                    '{reset_password_url}' => 'Reset Password URL',
	                    '{email_signature}'    => 'Email Signature',
	                    '{company_name}'       => 'Company Name'
	                ]
	            ],
	            [
	                'name'         => 'New User Sign Up',
	                'slug'         => 'new-user-signup',
	                'placeholders' => [
	                    '{firstname}'              => 'User Firstname',
	                    '{lastname}'               => 'User Lastname',
	                    '{email_verification_url}' => 'Email Verification URL',
	                    '{email_signature}'        => 'Email Signature',
	                    '{company_name}'           => 'Company Name'
	                ]
	            ]
	        ];
	        return $templates;
    	}
    	catch (\RuntimeException $e){
            Log::info('EmailController default_templates: '.$e->getMessage());
            return redirect()->route('settings.index')->with('error_message','Something went wrong! Please Try again');
        }        
    }
}
