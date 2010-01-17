<?php
/*
Plugin Name: BP Group Control
Plugin URI: http://danpolant.com/bp-group-control
Description: Evolving set of features that give site/group admins more control over groups
Author: Dan Polant
Author URI: http://danpolant.com
Version: .9 BETA
*/

//for wp_create_user
		
require_once(ABSPATH . WPINC . '/registration.php'); 
require_once(ABSPATH . 'wp-admin/includes/user.php');
/*
//hack required to load BP first
function bpgc_load_buddypress() {
	//buddypress is loaded
	if ( function_exists( 'bp_core_setup_globals' ) )
		return false;
	
	// Get the list of active sitewide plugins 
	$active_sitewide_plugins = maybe_unserialize( get_site_option( 'active_sitewide_plugins' ) );
	$bp_activated = $active_sitewide_plugins['buddypress/bp-loader.php'];
	
	//bp is not activated
	if ( !$bp_activated ){
		return false;
	}
	
	//bp is activated but not yet loaded
	if ( $bp_activated ) {
		return true;
	}

	return false;
}

//load bp if its activated but not loaded
if ( bpgc_load_buddypress() ){
	require_once( WP_PLUGIN_DIR . '/buddypress/bp-loader.php' );
}
*/

function bpgc_bp_loaded(){
	require ( WP_PLUGIN_DIR . "/BP-Group-Control/bpgc-templatetags.php");
	require ( WP_PLUGIN_DIR . "/BP-Group-Control/bpgc-classes.php");
}
add_action('bp_init', 'bpgc_bp_loaded');

function bpgc_core_add_admin_menu(){
	add_options_page( __("BP-Groups-Control", 'buddypress'), __("BP-Groups-Control", 'buddypress'), 10, __("BP-Groups-Control", 'buddypress'), "bpgc_admin_settings" );
}
add_action('admin_menu', 'bpgc_core_add_admin_menu');

function bpgc_admin_settings(){

	include( 'bpgc-admin.php');
}

function bpgc_register_options(){
	register_setting( 'bpgc-admin', 'bpgc-text-before-identifying' );
	register_setting( 'bpgc-admin', 'bpgc-identifying-enable-public' );
	register_setting( 'bpgc-admin', 'bpgc-identifying-enable-private' );
	register_setting( 'bpgc-admin', 'bpgc-member-control-enable-public' );
	register_setting( 'bpgc-admin', 'bpgc-member-control-enable-private' );
	register_setting( 'bpgc-admin', 'bpgc-site-admin-can-add-new' );
	register_setting( 'bpgc-admin', 'bpgc-site-admin-can-add-existing' );
	register_setting( 'bpgc-admin', 'bpgc-group-admin-can-add-existing' );
	register_setting( 'bpgc-admin', 'bpgc-group-admin-can-add-new' );
	register_setting( 'bpgc-admin', 'bpgc-group-admin-can-delete' );
	register_setting( 'bpgc-admin', 'bpgc-users-can-select-identifying' );
	register_setting( 'bpgc-admin', 'bpgc-site-admins-can-select-identifying' );
	register_setting( 'bpgc-admin', 'bpgc-group-admins-can-select-identifying' );
	register_setting( 'bpgc-admin', 'bpgc-users-can-create-groups' );
	register_setting( 'bpgc-admin', 'user-groups-have-member-control-existing');
	register_setting( 'bpgc-admin', 'user-groups-have-member-control-new');
}
add_action( 'admin_init', 'bpgc_register_options' );

function bpgc_scripts(){
	wp_enqueue_script('bpgc_screen', WP_PLUGIN_URL . '/BP-Group-Control/js/screen.js');
	//wp_enqueue_script('jquery-timers', WP_PLUGIN_URL . '/BP-Group-Control/js/jquery.timers-1.2.js');
		

}
add_action('init', 'bpgc_scripts');

function bpgc_stylesheet(){
	wp_enqueue_style('bpgc_screen', WP_PLUGIN_URL . '/BP-Group-Control/css/screen.css');
}
add_action('init', 'bpgc_stylesheet');


/**************** Ajax Response Functions ******************/

/* bpgc_create_screen_add_members_save()
 *
 * initiated via ajax in screen.js
 */
 
function bpgc_create_screen_add_members_save(){
	check_ajax_referer('bpgc_add_member_save');

	if (!isset($_REQUEST['existing']))
		bpgc_add_member_save('ajax');
	else
		bpgc_add_existing_member_save('ajax');
		
	//render message asynchronously
	bp_core_setup_message();
	bp_core_render_message();
}
add_action( 'wp_ajax_bpgc_create_screen_add_members_save', 'bpgc_create_screen_add_members_save');

/**************** Conditional Actions ***************/

/* bpgc_identifying_conditional_actions() handles option dependent components. Template tags are used to determine button and text placement
 * on a user-to-user basis
 */
function bpgc_identifying_conditional_actions(){

	if ( get_option( 'bpgc-identifying-enable-public' ) || get_option( 'bpgc-identifying-enable-private' )) {
		add_action( 'wp', 'bpgc_make_identifying', 3 );
		add_action( 'wp', 'bpgc_remove_identifying', 3 );
		add_action( 'bpgc_eject_member', 'bpgc_do_remove_identifying', 1);
		add_action( 'groups_remove_data_for_user', 'bpgc_do_remove_identifying', 1);
		add_action( 'groups_uninvite_user', 'bpgc_do_remove_identifying', 10, 2);
		add_action( 'bp_directory_members_item', 'bpgc_print_identifying_title');
		add_action('bp_profile_header_content', 'bpgc_print_identifying_title');
		//add_action( 'bp_group_manage_members_admin_item', 'bpgc_print_identifying_button' );
		
		if ( get_option( 'bpgc-users-can-select-identifying' ) || is_site_admin() ) {
			add_action( 'bp_profile_header_content', 'bpgc_print_identifying_button');
			add_action( 'bp_group_menu_buttons', 'bpgc_print_identifying_button' );
			add_action( 'bp_before_my_groups_list_item', 'bpgc_print_identifying_button' );			
		}
		
		if ( get_option( 'bpgc-group-admins-can-select-identifying' ) || is_site_admin() )
			add_action( 'bpgc_after_manage_links', 'bpgc_print_identifying_button' );
	}
}
add_action( 'plugins_loaded', 'bpgc_identifying_conditional_actions' );

function bpgc_member_control_conditional_actions(){
	global $bp;

	if ( isset( $_COOKIE['bp_new_group_id'] ) && $bp->current_action == 'create' )
		$group = bpgc_get_group( $_COOKIE['bp_new_group_id'] );
	else 
		$group = $bp->groups->current_group;
	
	if ( ( get_option( 'bpgc-member-control-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-member-control-enable-private' ) && $group->status == 'private' ) || ( get_option( 'bpgc-member-control-enable-public' ) && get_option( 'bpgc-member-control-enable-private' ) ) ){
		
		if ( bpgc_check_group_has_member_control( $group->id, 'new' ) && ( ( is_site_admin() && get_option('bpgc-site-admin-can-add-new') ) || ( $bp->is_item_admin && get_option('bpgc-group-admin-can-add-new') ) ) ){
			$add_members = new BPGC_Add_Members_Group_Extension;
			
			add_action( "wp", array( &$add_members, "_register" ), 2 );
			add_action( 'wp', 'bpgc_delete_member_screen', 4 );
			
			if ( get_option( 'bpgc-group-admin-can-delete' ) || is_site_admin() )
				add_action( 'bpgc_delete_member_link', 'bpgc_the_delete_members_link' );
				
			add_action( 'wp', 'bpgc_confirm_delete_member', 4 );
			add_action( 'wp', 'bpgc_confirm_eject_member', 4 );
			add_action( 'bp_group_manage_members_admin_item', 'bpgc_manage_members_links' );
		}
		if ( bpgc_check_group_has_member_control( $group->id, 'existing' ) && ( ( is_site_admin() && get_option('bpgc-site-admin-can-add-existing') ) || ( $bp->is_item_admin && get_option('bpgc-group-admin-can-add-existing') ) ) ){
			$add_existing = new BPGC_Add_Existing_Members_Group_Extension;
			add_action( "wp", array( &$add_existing, "_register" ), 2 );
		}
	}
}
add_action( 'plugins_loaded', 'bpgc_member_control_conditional_actions' );

function bpgc_restrictions() {
	global $bp; 
	
	if ( !current_user_can( 'activate_plugins' ) && !get_option( 'bpgc-users-can-create-groups' ) ){
		bp_core_remove_subnav_item( $bp->groups->slug, 'create');
		//bp_core_remove_subnav_item( $bp->groups->slug, 'leave-group');
	}
}
add_action( 'plugins_loaded', 'bpgc_restrictions' );

/**************** Screen functions ***************/

function bpgc_delete_member_screen(){
	global $bp;
	
	if ( $bp->current_component == $bp->groups->slug && 'manage-members' == $bp->action_variables[0] ) {
			
		if ( 'delete' == $bp->action_variables[1] && is_numeric( $bp->action_variables[2] ) ) {
			$user_id = $bp->action_variables[2];	
	
			$temp = apply_filters( 'bpgc_delete_member', 'bp-group-control/confirm-delete-member' );
			bp_core_load_template($temp);
		}
	}
}

/**************** Action functions ***************/

function bpgc_confirm_delete_member(){
	global $bp, $wpdb;
	
	if ( $bp->current_component == $bp->groups->slug && 'manage-members' == $bp->action_variables[0] ) {
		
		if ( !$bp->is_item_admin )
			return false;
			
		if ( 'delete-confirm' == $bp->action_variables[1] ) {
			$user_id = $_POST['user-id'];
			
			if ( $_POST['reassign'] == 'reassign-to' )
				$reassign = $_POST['reassign-select'];
			elseif ( $_POST['reassign'] == 'reassign-me' )
				$reassign = $bp->loggedin_user->id;
			//print_r($_POST['reassign-select']);
			//bp_core_add_message( $reassign );
				//return;

			if ( !check_admin_referer( 'groups_delete_member' ) )
				return false;
			
			$creator_id = get_usermeta( $user_id, "created_by" );
				
			if ( ( $creator_id == $bp->loggedin_user->id ) || ( is_site_admin() && $user_id != $bp->loggedin_user->id ) ){
				if ( wp_delete_user( $user_id, $reassign ) ) {				
					//hack for old 1.1s ???
					$wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->users WHERE ID = %d", $user_id) );
					
					bp_core_add_message( "User deleted" );
				} else {
					bp_core_add_message( "Error deleting user" );
				}
			} else {
				bp_core_add_message( __('You cannot delete that user because you did not create him/her', 'bp-group-control'), 'error' );
			}
		}
	}
}

function bpgc_confirm_eject_member(){
	global $bp, $wpdb, $group;

	if ( $bp->current_component == $bp->groups->slug && 'manage-members' == $bp->action_variables[0] ) {
		
		if ( !$bp->is_item_admin )
			return false;

		if ( 'eject' == $bp->action_variables[1] && is_numeric( $bp->action_variables[2] ) ) {
			$user_id = $bp->action_variables[2];	
			
			if ( !check_admin_referer( 'groups_eject_member' ) )
				return false;
				
			if ( $user_id != $bp->loggedin_user->id ) {	
				if ( groups_uninvite_user($user_id, $bp->groups->current_group->id ) ){			
					bp_core_add_message( "User ejected" );
					do_action('bpgc_eject_member', $user_id );
				} else {
					bp_core_add_message( "Error ejecting user" );
				}
			} else {
				bp_core_add_message( __('You cannot eject yourself from a group', 'bp-group-control'), 'error' );
			}
		}
	}
}

/* 
 * bpgc_make_identifying()
 * 
 * updates usermeta to save a key value pair of bpgc_identifier and group id
 */
function bpgc_make_identifying(){
	global $bp, $current_user;
	
	if ( !( $bp->current_component == $bp->groups->slug && 'identifying' == $bp->current_action ) )
		return;

	check_admin_referer('bpgc_make_identifying');

		if ($bp->action_variables[0] && ( is_site_admin() || $bp->is_item_admin ) )
			$user_id = $bp->action_variables[0];
		else
			$user_id = $current_user->ID;
	
	bpgc_do_make_identifying($user_id);
	bp_core_redirect( bpgc_get_user_permalink($user_id) );
}

function bpgc_remove_identifying(){
	global $bp, $current_user;
	
	if ( !( $bp->current_component == $bp->groups->slug && 'remove-identifying' == $bp->current_action ) )
		return;
		
	check_admin_referer('bpgc_remove_identifying');	
	
	if ($bp->action_variables[0] && is_site_admin() )
		$user_id = $bp->action_variables[0];
	else
		$user_id = $current_user->ID;
	
	$user = get_userdata( $user_id );
	$group_name = $bp->groups->current_group->name;
		
	if ( bpgc_do_remove_identifying( '', $user_id )){
		if ( $user_id == $bp->loggedin_user->id )
			$msg = $group_name . " is no longer your identifying group";
		else
			$msg = $group_name . " is no longer " . $user->display_name . "'s identifying group";
			
		do_action('bpgc_remove_identifying');	
		
		bp_core_add_message(  __($msg, 'bp-group-control') );
	} else{
		bp_core_add_message( __('Error removing identifying group, please try again.', 'bp-group-control'), 'error' );
	}
	
	
	
	bp_core_redirect( bpgc_get_user_permalink($user_id) );
}

/**************** Utility functions ***************/

function bpgc_check_group_has_member_control( $group_id, $member_control_type ){

	$group = bpgc_get_group( $group_id );
	$creator_id = $group->creator_id;
	$creator = get_userdata( $creator_id );
	$creator_login = $creator->user_login;
	
	if ( $member_control_type == 'new' ) {
		if ( get_option( 'user-groups-have-member-control-new' ) || is_site_admin( $creator_login ) )
			return true;
	}
	
	if ( $member_control_type == 'existing' ) {
		if ( get_option( 'user-groups-have-member-control-existing' ) || is_site_admin( $creator_login ) )
			return true;
	}	
		
	return false;
}
	
/* bpgc_add_existing_member_save()
 *
 * Utility function used by edit_screen_save() and bpgc_create_screen_add_existing_members_save()
 */ 
function bpgc_add_existing_member_save($method = NULL) {
	global $bp;
	
	if ($method == 'ajax')
		$group_id = $_COOKIE['bp_new_group_id'];
	else
		$group_id = $bp->groups->current_group->id;
		
	if ( empty($_POST['username']) ) {
		bp_core_add_message( __('Please fill in a username', 'groupforce'), 'error' );
	}
	else {
		if ($user_data = get_userdatabylogin($_POST['username'])) {
			if (!groups_is_user_member($user_data->ID, $group_id)) {
				$user = new BP_Groups_Member;
						
				$user->group_id = $group_id;
				$user->user_id = $user_data->ID;
				$user->inviter_id = 0;
				
				if ( isset( $_POST['group-admin'] ) ){
					$user->is_admin = 1;
					$user->user_title = 'Group Admin';
				} else {
					$user->is_admin = 0;
					$user->user_title = 'Member';
				}
				
				$user->is_mod = 0;
				$user->is_banned = 0;
				$user->date_modified = time();
				$user->is_confirmed = 1;
				$user->comments = '';
				$user->invite_sent = 1;
	
				if ($user->save()) {
					bpgc_add_members_update_meta($user_data->ID, $group_id);
					return true;
				}
				else {
					bp_core_add_message( __("Error adding user.", 'groupforce'), 'error' );
					return false;
				}
			}
			else {
				bp_core_add_message( __("That user is already part of this group.", 'groupforce'), 'error' );
				return false;
			}
		}
		else {
			bp_core_add_message( __("That username does not exist in this site.", 'groupforce'), 'error' );
			return false;
		}
	}
	return false;
}

/* bpgc_add_member_save()
 *
 * Utility function used by edit_screen_save() and bpgc_create_screen_add_members_save()\
 * 
 * TODO:
 * - limit length of fields
 * - correctly sanitize user_nicename
 */

function bpgc_add_member_save($method = NULL){
	global $bp, $current_user;
	
	if ( isset( $_POST['send-email'] ) && ( empty($_POST['username']) || empty($_POST['email']) || empty($_POST['name']) ) ){
			bp_core_add_message( __('Please fill in all of the required fields', 'groupforce'), 'error' );
	}
	elseif ($_POST['pass'] != $_POST['conf_pass'] && !isset( $_POST['send-email'] ) ){
		bp_core_add_message( __("You entered passwords that don't match, please try again.", 'bp-group-control'), 'error' );
	}
	elseif ( strlen( $_POST['pass'] ) < 8 && !isset( $_POST['send-email'] ) ) {
		bp_core_add_message( __("The password must be at least 9 characters", 'bp-group-control'), 'error' );
	}
	elseif ( !is_email( $_POST['email'] ) ){
		bp_core_add_message( __("That is not a valid email address", 'bp-group-control'), 'error' );
	}
	else {
		if ( !empty( $_POST['send-email'] ) )
			$pw = generate_random_password();
		else
			$pw = $_POST['pass'];
			
		$new_user = array(
			'user_pass' => $pw,
			'user_login' => $_POST['username'],
			'user_nicename' => strip_tags($_POST['username']),
			'user_email' => $_POST['email'],
			'display_name' => $_POST['name']);

		if (!email_exists( $_POST['email'] ) ) {
			if ( !get_userdatabylogin( $_POST['username'] ) ) {
				if ($user_id = wp_insert_user($new_user)) {
					get_currentuserinfo();
					
					$user = new BP_Groups_Member;
					
					if ($method == 'ajax')
						$group_id = $_COOKIE['bp_new_group_id'];
					else
						$group_id = $bp->groups->current_group->id;
					
					$group = bpgc_get_group( $group_id );
					
					$user->group_id = $group_id;
					$user->user_id = $user_id;
					$user->inviter_id = 0;
					
					if ( !empty( $_POST['group-admin'] ) ){
						$user->is_admin = 1;
						$user->user_title = 'Group Admin';
					} else {
						$user->is_admin = 0;
						$user->user_title = 'Member';
					}
						
					$user->is_mod = 0;
					$user->is_banned = 0;
					$user->date_modified = time();
					$user->is_confirmed = 1;
					$user->comments = '';
					$user->invite_sent = 1;
					
					if ( $user->save() ) {
						if ( !empty( $_POST['send'] ) || !empty( $_POST['send-email']) ){
							$to = $_POST['email'];
							$subject = "Registration notice at " . get_option('blogname');
							$message = "Message from " . get_option('blogname') . ": 
"
. $current_user->display_name . " has made you a member of the group " . $group->name . " on " . get_option('blogname') . ". 
							
Password: " . $pw . "
Username: " . $_POST['username'];
							 
							if ( !wp_mail( $to, $subject, $message ) )
								bp_core_add_message( __("Email was not delivered correctly.", 'groupforce'), 'error' );
						}
						
						xprofile_set_field_data('Name', $user_id, $_POST['name']);
						bpgc_add_members_update_meta($user_id, $group_id);
						return true;
					}
					else {
						bp_core_add_message( __("Could not add user.", 'groupforce'), 'error' );
					}
				}
				else {
					bp_core_add_message( __("Add user failed.", 'groupforce'), 'error' );
				}
			} else {
				bp_core_add_message( __("That username already exists.", 'bp-group-control'), 'error' );
			}
		} else {
			bp_core_add_message( __("That email already exists.", 'bp-group-control'), 'error' );
		}
	}
	return false;
}

/*
 * bpgc_get_group($id)
 * 
 * returns a group object from an id
 */

function bpgc_get_group($group_id){
	if ($group = new BP_Groups_Group($group_id))
		return $group;
		
	return false;
}

/*
 * bpgc_add_members_update_meta($id, $group_id)
 * 
 * updates groupmeta for activity and member count, updates usermeta for user's last activity,
 * adds success message
 */
 
function bpgc_add_members_update_meta($user_id, $group_id){
	global $bp;
	
	groups_update_groupmeta( $user_id, 'total_member_count', (int) groups_get_groupmeta( $group_id, 'total_member_count') + 1 );
	groups_update_groupmeta( $user_id, 'last_activity', time() );
	
	if (!empty($_POST['make-identifying']))
		bpgc_do_make_identifying($user_id);
			
	update_usermeta( $user_id, 'last_activity', time() );
	update_usermeta( $user_id, 'created_by', $bp->loggedin_user->id );
	bp_core_add_message(__("Added user " . $_POST['username'] . " to " . bpgc_get_group($group_id)->name, 'bp-groups-control'));

	do_action('bpgc_add_member');
}

function bpgc_do_make_identifying($user_id = false, $group_id = false){
	global $bp;
	
	if (!$group_id) {
		if($bp->action_variables[1] == 'add-members' || $bp->action_variables[1] == 'add-members-existing')
			$group_id = $_COOKIE['bp_new_group_id'];
		else
			$group_id = $bp->groups->current_group->id;
	}
	
	if (!$user_id)
		$user_id = $bp->loggedin_user->id;
	
	$group_name = $bp->groups->current_group->name;
	$user = get_userdata( $user_id );
	
	if (bpgc_has_identifying())
		bpgc_do_remove_identifying( '', $user_id );
	
	if ( !update_usermeta( $user_id, 'bpgc_identifying', $group_id)) {
		bp_core_add_message(  __('There was an error making' . $group_name . 'group your identifying group. Please try again. ' . $user_id . ' ' . $group_id, 'bp-group-control'), 'error' );
		
		do_action('bpgc_make_identifying');
		return false;
	} else {
		if ( $user_id == $bp->loggedin_user->id )
			$msg = 'You have made ' . $group_name . ' your identifying group.';
		else 
			$msg = $group_name . " is now " . $user->display_name . "'s identifying group.";
		
		bp_core_add_message( __($msg, 'bp-group-control') );
	}
	
	do_action('bpgc_do_make_identifying');
}

function bpgc_do_remove_identifying( $group_id = false, $user_id ){

	if ( delete_usermeta( $user_id, 'bpgc_identifying') ) {
		return true;
	}

	return false;
}
?>