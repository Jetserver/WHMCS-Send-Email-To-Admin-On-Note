<?php
/*
*
* Send Email To Admin On Note
* Created By Idan Ben-Ezra
*
* Copyrights @ Jetserver Web Hosting
* www.jetserver.net
*
* Hook version 1.0.0
*
**/

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

/*********************
 Send Email To The Admin On Note Settings
*********************/
function jetserverSendEmailToAdminOnNote_settings()
{
	global $CONFIG, $customadminpath;

	return array(
		'apiuser' 		=> '',

		// %1$s - Ticket ID shown to client
		// %2$s - Note ID
		'ticket_subject' 	=> '[Note ID: %2$s] New Support Ticket Note for Ticket %1$s',

		// %1$s - Admin Full Name
		// %2$s - Admin Username
		// %3$s - Ticket ID
		// %4$s - Note Content
		// %5$s - Ticket Subject
		// %6$s - Client Name
		// %7$s - Ticket Priority
		// %8$s - Ticket Department
		'ticket_message'	=> 'A new support ticket note has been added by %1$s (%2$s).<br /><br />Client: %6$s<br />Department: %8$s<br />Ticket Subject: %5$s<br />Priority: %7$s<br />---<br />%4$s<br />---<br /><br />You can view this ticket note through the admin area at the url below.<br /><br />' . $CONFIG['SystemURL'] . '/' . ($customadminpath ? $customadminpath : 'admin') . '/supporttickets.php?action=viewticket&id=%3$s',
	);
}
/********************/

function jetserverSendEmailToAdminOnNote_sendEmail($vars)
{
	$settings = jetserverSendEmailToAdminOnNote_settings();

	$sql = "SELECT *
		FROM tbladmins
		WHERE id = '{$vars['adminid']}'";
	$result = mysql_query($sql);
	$admin_details = mysql_fetch_assoc($result);

	$sql = "SELECT *
		FROM tbltickets
		WHERE id = '{$vars['ticketid']}'";
	$result = mysql_query($sql);
	$ticket_details = mysql_fetch_assoc($result);

	$client_name = $ticket_details['name'];

	if($ticket_details['userid'])
	{
		$sql = "SELECT *
			FROM tblclients
			WHERE id = '{$ticket_details['userid']}'";
		$result = mysql_query($sql);
		$client_details = mysql_fetch_assoc($result);

		$client_name = $client_details['firstname'] . ' ' . $client_details['lastname'];
	}

	$sql = "SELECT *
		FROM tblticketnotes
		WHERE ticketid = '{$vars['ticketid']}'
		ORDER BY id DESC
		LIMIT 1";
	$result = mysql_query($sql);
	$note_details = mysql_fetch_assoc($result);

	$sql = "SELECT *
		FROM tblticketdepartments
		WHERE id = '{$ticket_details['did']}'";
	$result = mysql_query($sql);
	$department_details = mysql_fetch_assoc($result);

	localAPI('sendadminemail', array(
		'type'			=> 'support',
		'deptid'		=> $ticket_details['did'],
		'customsubject'		=> sprintf($settings['ticket_subject'], $ticket_details['tid'], $note_details['id']),
		'custommessage'		=> sprintf($settings['ticket_message'], "{$admin_details['firstname']} {$admin_details['lastname']}", $admin_details['username'], $vars['ticketid'], nl2br($vars['message']), $ticket_details['title'], $client_name, $ticket_details['urgency'], $department_details['name']),
	), $settings['apiuser']);
}

add_hook('TicketAddNote', 0, 'jetserverSendEmailToAdminOnNote_sendEmail');

?>
