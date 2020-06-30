<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        $this->load_default_templates();
		$templates = EmailTemplate::get();

		return view('admin.emails.index', compact('templates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$template = EmailTemplate::find($id);

		return view('admin.emails.email_template', compact('template'));
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
		$template = EmailTemplate::find($id);
		$input    = $request->except(['_method', '_token']);

		$update = $template->update($input);

		if ($update)
		{
			set_alert('success', __('messages._updated_successfully', ['Name'=>'Email Template']));

			return redirect('/admin/emails');
		}
	}

    /**
     * Loads default templates data into the database if not already exists.
     */
    private function load_default_templates()
    {
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

    /**
     * Contains the Default Email Templates to be used in the system.
     * You can add or remove Templates in this function & it will reflect  * on the Email Templates Module
     *
     * @return [array]      The default email templates with their placeholders information
     */
    public function default_templates()
    {
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
