<?php 

function bpgc_manage_members_links(){
	?><script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#bpgc-delete").unbind("click");
        });
            </script>
    
	<h5><?php 
	if ( bpgc_is_deletable() )
		do_action( "bpgc_delete_member_link" );
        
	if ( bpgc_is_ejectable() )
		bpgc_the_eject_members_link();
	
	if ( bpgc_identifying_button_is_printable() )
		do_action( "bpgc_after_manage_links" ); ?></h5><?php
}

function bpgc_the_delete_members_confirm_action( $args = '' ) { 
	global $bp;
	
	if ( is_numeric( $bp->action_variables[2] ) )
		$user_id = $bp->action_variables[2];
		
	echo bpgc_get_delete_members_link( $args = array( 'action' => 'delete-confirm', 'user_id' => '' ) );
}

function bpgc_the_delete_user_id(){
	global $bp;
	
	if ( is_numeric( $bp->action_variables[2] ) )
		echo $bp->action_variables[2];
}

function bpgc_the_delete_member_dropdown(){
	global $wpdb;
	
	$all_logins = $wpdb->get_results("SELECT ID, user_login FROM $wpdb->users, $wpdb->usermeta WHERE $wpdb->users.ID = $wpdb->usermeta.user_id AND meta_key = '".$wpdb->prefix."capabilities' ORDER BY user_login");
	
	$user_dropdown = '<select name="reassign-select">';
	foreach ( (array) $all_logins as $login )
		if ( $login->ID == $current_user->ID || !in_array($login->ID, $userids) )
			$user_dropdown .= "<option value=\"" . esc_attr($login->ID) . "\">{$login->user_login}</option>";
	$user_dropdown .= '</select>';
	
	echo $user_dropdown;
}
	
function bpgc_is_deletable(){
	global $bp, $members_template;
	
	$user_id = $members_template->member->user_id;
	$creator_id = get_usermeta( $user_id, "created_by" );

	if ( ( $creator_id == $bp->loggedin_user->id ) || ( is_site_admin() && $user_id != $bp->loggedin_user->id ) )
		return true;
		
	return false;
}

function bpgc_the_delete_members_link( $args = '' ) { ?>
    <span class='small'><a href="<?php echo bpgc_get_delete_members_link( $args ) ?>" class="confirm bpgc-delete" id="bpgc-delete" title="<?php _e( 'Delete account', 'bp-group-control' ); ?>"><?php _e( 'Delete account', 'bp-group-control' ); ?></a></span> <?php
}
	function bpgc_get_delete_members_link( $args = '' ) {
		global $members_template, $groups_template, $bp;

		$defaults = array(
			'user_id' => $members_template->member->user_id,
			'group' => &$groups_template->group,
			'action' => "delete"
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		return apply_filters( 'bpgc_get_delete_members_link', wp_nonce_url( bp_get_group_permalink( $group ) . '/admin/manage-members/' . $action . '/' . $user_id, 'groups_delete_member' ) );
	}

function bpgc_is_ejectable(){
	global $bp, $members_template;
	
	$user_id = $members_template->member->user_id;
	$user = get_userdata( $user_id );
	
	if ( $user_id != $bp->loggedin_user->id && !is_site_admin( $user->user_login ) )
		return true;
		
	return false;
}
	
function bpgc_the_eject_members_link( $args = '' ) { ?>
	<span class='small'><a href="<?php echo bpgc_get_eject_members_link( $args ) ?>" class="confirm bpgc-delete" title="<?php _e( 'Eject', 'bp-group-control' ); ?>"><?php _e( 'Eject user from group', 'bp-group-control' ); ?></a></span> <?php
}
	function bpgc_get_eject_members_link( $args = '' ) {
		global $members_template, $groups_template, $bp;

		$defaults = array(
			'user_id' => $members_template->member->user_id,
			'group' => &$groups_template->group
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		return apply_filters( 'bpgc_get_eject_members_link', wp_nonce_url( bp_get_group_permalink( $group ) . '/admin/manage-members/eject/' . $user_id, 'groups_eject_member' ) );
	}

function bpgc_identifying_button_is_printable(){
	global $bp, $members_template;
	
	$user_id = $members_template->member->user_id;
	$user = get_userdata( $user_id );
	
	if ( !is_site_admin( $user->user_login ) )
		return true;
		
	return false;
}
	
function bpgc_print_identifying_button( $group_id = false ){
	global $bp, $groups_template, $current_user, $identifying_group, $members_template;

	if ( !$group_id ){
		if ( $groups_template->group ) 
			$group =& $groups_template->group; 
		elseif ( $identifying_group )
			$group =& $identifying_group;
	} else {
		$group = bpgc_get_group($group_id);
	}
	
	if ( $bp->displayed_user->id )
		$user_id = $bp->displayed_user->id;
	else
		$user_id = $members_template->member->user_id;
		
    if ( ( !groups_is_user_member( $current_user->ID, $group->id ) && bp_is_group_home() ) )
		return false; 
		
	if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ){	
	
		//if it is a group home page or it is one's own profile ...
		if ( bp_is_group_home() || $bp->displayed_user->id == $bp->loggedin_user->id ){
			
			if ( bpgc_has_identifying_group($group->id) ){ ?>
				<div class="generic-button group-button">
					<a class="leave-group" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . "/remove-identifying", 'bpgc_remove_identifying')?>">Remove identifying</a>
				</div>
	
	<?php } else{ ?>
	
				<div class="generic-button group-button">
					<a class="send-message" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . "/identifying", 'bpgc_make_identifying')?>">Make identifying</a>
				</div>
		
		<?php }
		}	
		//if it is a site admin and not his/her own profile and not a group homepage ...
		else if ( is_site_admin() || ( $bp->is_item_admin && bp_is_group_admin_screen( 'manage-members' ) ) ){	

			if ( bpgc_has_identifying_group($group->id, $user_id )){ ?>
				<div class="generic-button group-button">
					<a class="leave-group" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . "/remove-identifying/" . $user_id, 'bpgc_remove_identifying')?>">Remove identifying</a>
				</div>
				
	<?php } else { ?>        
	
				 <div class="generic-button group-button">
								<a class="send-message" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . "/identifying/" . $user_id, 'bpgc_make_identifying')?>">Make identifying</a>
				 </div>
					
		<?php }
		}
	}
}

function bpgc_print_identifying_title(){
	if ( bpgc_has_identifying() ) : ?>
    
		<div id='bpgc-profile-identifying-group'><?php echo esc_attr( get_option("bpgc-text-before-identifying") ) ?> <a href= "<?php bpgc_the_identifying_group_permalink() ?>"> <?php bpgc_the_identifying_group_name() ?></a></div> 
        
  <?php //bpgc_print_identifying_button(); ?>
  
  <?php endif;
}

function bpgc_user_permalink($user_id = false){
	echo bpgc_get_user_permalink($user_id);
}
	
	function bpgc_get_user_permalink($user_id = false ){
		
		if (!$user_id)
			return bp_user_link();
			
		$user_info = get_userdata($user_id);
		$user_name = $user_info->user_nicename;
		
		return get_option('siteurl') . "/" . BP_MEMBERS_SLUG . "/" . $user_name;
	}


function bpgc_the_identifying_group_name(){
	echo bpgc_get_identifying_group_name();
}
	
	function bpgc_get_identifying_group_name() {
		global $identifying_group;
		
		return $identifying_group->name;
	}

function bpgc_the_identifying_group_permalink(){
	echo bpgc_get_identifying_group_permalink();
}

	function bpgc_get_identifying_group_permalink(){
		global $identifying_group;
		
		return bp_get_group_permalink( $identifying_group );
		
	}

	
function bpgc_has_identifying($user_id = false){
	global $bp, $site_members_template, $identifying_group;
	
	if (!$user_id) {
		if ($bp->displayed_user->id)
			$user_id = $bp->displayed_user->id;
		elseif ($site_members_template->member->id)
			$user_id = $site_members_template->member->id;
		else 
			$user_id = $bp->loggedin_user->id;
	}
	
	if ($group_id = get_usermeta($user_id, 'bpgc_identifying')) {
		$identifying_group = new BP_Groups_Group( $group_id );
		
		if ( ( get_option( 'bpgc-identifying-enable-public' ) && $identifying_group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $identifying_group->status == 'private' ) )
			return $identifying_group;
	}
	
	return false;
}

function bpgc_has_identifying_group($group_id, $user_id = false ) {
	global $bp, $site_members_template, $members_template;
	
	if (!$user_id) {
		if ($bp->displayed_user->id)
			$user_id = $bp->displayed_user->id;
		elseif ($site_members_template->member->id)
			$user_id = $site_members_template->member->id;
		elseif ($members_template->member->user_id)
			$user_id = $members_template->member->user_id;
		else 
			$user_id = $bp->loggedin_user->id;
	}
	
	if ( $group_id && get_usermeta( $user_id, 'bpgc_identifying' ) == $group_id )
		return true;
	
	return false;
}
