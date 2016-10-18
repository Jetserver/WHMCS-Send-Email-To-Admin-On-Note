# WHMCS-Send-Email-To-Admin-On-Note

This hook will send email notification whenever a ticket note is added. Email will be sent only to the admins assigned to that department.

This can be very useful when several admins are working remotely, so you can be sure they will get your notes.

# Installation

Upload the hook file to your WHMCS hooks folder (“includes/hooks“).

You can edit the hook file with your favorite text editor, and edit the settings –

	return array(
		'apiuser' 		=> 'apiuser',
		'ticket_subject' 	=> 'Email subject goes here',
		'ticket_message'	=> 'Email body goes here',
	);
  
  Example usage –

* apiuser – Any admin user name
* ticket_subject – [Note ID: %2$s] New Support Ticket Note added for Ticket %1$s
%1$s – Ticket ID
%2$s – Note ID
* ticket_message –

  This note added by %1$s (%2$s).

  %4$s

  You can view this ticket note through the admin area at the url below.

  $CONFIG['SystemURL'] . '/' . ($customadminpath ? $customadminpath : 'admin') .
 '/supporttickets.php?action=viewticket&id=%3$s
 
 --
* %1$s – Admin Full Name
* %2$s – Admin Username
* %3$s – Ticket ID
* %4$s – Note Content
