<?php 

date_default_timezone_set('Asia/Kolkata');

/**
 * Get the date & time as per the format set in the settings
 *
 * @param  int  $timestamp  The timestamp
 *
 * @return str  formatted date and/or time
 */
function display_date_time($datetime)
{
	$timestamp = strtotime($datetime);

	if (get_settings('date_format') != '' && get_settings('time_format') != '')
	{
		return date(get_settings('date_format').'  '.get_settings('time_format'), $timestamp);
	}
	else

	if (get_settings('date_format') != '' && get_settings('time_format') == '')
	{
		return date(get_settings('date_format').'  h:i A', $timestamp);
	}
	else

	if (get_settings('date_format') == '' && get_settings('time_format') != '')
	{
		return date('d-m-Y  '.get_settings('time_format'), $timestamp);
	}
	else
	{
		return date('d-m-Y  h:i A', $timestamp);
	}
}

?>