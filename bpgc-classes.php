<?php
class BPGC_Add_Members_Group_Extension extends BP_Group_Extension {	

	var $enable_nav_item = false;
	
	function bpgc_add_members_group_extension() {
				
		$this->name = 'Add';
		$this->slug = 'add-members';

		$this->create_step_position = 21;
		//$this->nav_item_position = 31;
	}

	function create_screen() {
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false; 
		?>
        
		 <p>Add a new member to the site and to this group</p>
        <div id='add-members-fields'>    
            <label for="username">Username: </label><input type="text" name="username"></input>
            <label for="username">Name: </label><input type="text" name="name"></input>
            <label for="email">Email: </label><input type="text" name="email"></input><br/>
            <label for="pass">Password: </label><input type="password" name="pass"></input>
            <label for="pass">Confirm Password: </label><input type="password" name="conf_pass"></input>
            <label for="send">Send email</label><input type="checkbox" name="send" class="bpgc-checkbox" name="send" />
            
            <div class="bp-widget bpgc-cb">
                <h4>Options</h4>
                <table>
                    <tr>
                        <th><label for="group-admin[]">Make this user a group admin </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="group-admin[]" /></td>
                    </tr>
                    <tr>
                        <th><label for="send-email[]">Use random password and send to user via email </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="send-email[]" /></td>
                    </tr>

                
                
                    <?php global $bp, $current_user;
                    
                    $group = $bp->groups->current_group;
                    
                    if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ) : ?>
                    
                        <?php
                        
                        if ( ( is_site_admin( $current_user->user_login ) && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->context->is_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
                    
                            <tr>
                                <th><label for="make-identifying[]">Make this an identifying membership </label></th>
                                <td><input type="checkbox" class="bpgc-checkbox" name="make-identifying[]" /></td>
                            </tr>
                            
                        <?php endif; ?>
                        
                    <?php endif; ?>
                    
                    <?php do_action ("bpgc_add_member_extra_items") ?>
                </table>
            

			<?php wp_nonce_field( 'bpgc_add_member_save', '_wpnonce-add-member-save' ); ?>
                <input type="button" name="save-members" id="save-members" value = "Add this member"/>
                <p>Press 'Next' to move on.</p>
                
                <span class="bpgc-ajax-loader"></span>
                
                <h4>Before you go on ... </h4>
                <table>
                    <tr>
                        <th><label for="remove-creator[]">Don't add me to this group </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="remove-creator[]" /></td>
                    </tr>
                </table>
			</div>
        </div>
		<?php
		wp_nonce_field( 'groups_create_save_' . $this->slug );
	}
	
	function create_screen_save() {
		
		global $bp;
		
		check_admin_referer( 'groups_create_save_' . $this->slug );
		
		if (isset($_POST['remove-creator']))
			groups_uninvite_user($bp->loggedin_user->id, $_COOKIE['bp_new_group_id']);
	}

	function edit_screen() {
		if ( !bp_is_group_admin_screen( $this->slug ) )
			return false; ?>

		<h2><?php echo attribute_escape( $this->name ) ?></h2>
        <div id='add-members-fields'>    
            <label for="username">Username: </label><input type="text" name="username"></input>
            <label for="username">Name: </label><input type="text" name="name"></input>
            <label for="email">Email: </label><input type="text" name="email"></input><br/>
            <label for="pass">Password: </label><input type="password" name="pass"></input>
            <label for="pass">Confirm Password: </label><input type="password" name="conf_pass"></input>
            <label for="send">Send email</label><input type="checkbox" name="send" class="bpgc-checkbox" name="send[]" />
            
            <div class="bp-widget bpgc-cb">
                <h4>Options</h4>
                <table>
                    <tr>
                        <th><label for="group-admin">Make this user a group admin </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="group-admin[]" /></td>
                    </tr>
                    <tr>
                        <th><label for="send-email">Use random password and send to user via email </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="send-email[]" /></td>
                    </tr>       
                
                    <?php global $bp, $current_user;
                    
                    $group = $bp->groups->current_group;
                    
                    if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ) : ?>
                    
                        <?php
                        
                        if ( ( is_site_admin( $current_user->user_login ) && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->is_item_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
                    
                            <tr>
                                <th><label for="make-identifying">Make this an identifying membership </label></th>
                                <td><input type="checkbox" class="bpgc-checkbox" name="make-identifying[]" /></td>
                            </tr>
                            
                        <?php endif; ?>
                        
                    <?php endif; ?>
                    
                    <?php do_action ("bpgc_add_member_extra_items") ?>
                </table>
            </div>
        </div>

		<input type="submit" name="save" value="Save" />

		<?php
		wp_nonce_field( 'groups_edit_save_' . $this->slug );
	}

	function edit_screen_save() {
		global $bp;

		if ( !isset( $_POST['save'] ) )
			return false;

		check_admin_referer( 'groups_edit_save_' . $this->slug );

		if ( !bpgc_add_member_save() )
			bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . '/admin/' . $this->slug );
	}
	
	function widget_display() {
	}
}

/* bpgc_add_existing_members_group_extension
 *
 * add members already in BP to a group
 */
 
class BPGC_Add_Existing_Members_Group_Extension extends BP_Group_Extension {	

	var $enable_nav_item = false;
	
	function bpgc_add_existing_members_group_extension() {
		$this->name = 'Add existing';
		$this->slug = 'add-members-existing';

		$this->create_step_position = 25;
	}

	function create_screen() {
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false; 
		?>
		<p>Add a member already registered in the site</p>
        <div id='add-members-fields'>    
            <label for="username">Username: </label><input type="text" name="username"></input><br/>
            
             <div class="bp-widget bpgc-cb">
                <h4>Options</h4>
                <table>
                    <tr>
                        <th><label for="group-admin">Make this user a group admin </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="group-admin[]" /></td>
                    </tr>
                    
            <?php global $bp;
            
			$group = $bp->groups->current_group;
			
			if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ) : ?>
            
            <?php global $bp;
					
					if ( ( is_site_admin() && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->context->is_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
            
                        <tr>
                            <th><label for="make-identifying">Make this an identifying membership </label></th>
                            <td><input type="checkbox" class="bpgc-checkbox" name="make-identifying[]" /></td>
                        </tr>
                    
                	<?php endif; ?>
                
            	<?php endif; ?>
            	</table>
            </div>
        </div>
        
        <?php wp_nonce_field( 'bpgc_add_member_save', '_wpnonce-add-member-save' ); ?>  
        <input type="button" name="save-members-existing" id="save-members-existing" value = "Add this member"/>
        
        <span class="bpgc-ajax-loader"></span>
        <p>Press 'Next' to move on.</p>
		<?php
		wp_nonce_field( 'groups_create_save_' . $this->slug );
	}
	
	//asyncronous save, so just verify the referer ...
	function create_screen_save() {
		
		global $bp;

		check_admin_referer( 'groups_create_save_' . $this->slug );
	}

	function edit_screen() {
		global $bp;
		
		if ( !bp_is_group_admin_screen( $this->slug ) )
			return false; ?>

		<h2><?php echo attribute_escape( $this->name ) ?></h2>
        <div id='add-members-fields'>    
            <label for="username">Username: </label><input type="text" name="username"></input><br/>
            
             <div class="bp-widget bpgc-cb">
                <h4>Options</h4>
                <table>
                    <tr>
                        <th><label for="group-admin">Make this user a group admin </label></th>
                        <td><input type="checkbox" class="bpgc-checkbox" name="group-admin[]" /></td>
                    </tr>
                    
            <?php global $bp;
            
			$group = $bp->groups->current_group;
			
			if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ) : ?>
            
            <?php global $bp, $current_user;
				
					if ( ( is_site_admin( $current_user->user_login ) && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->is_item_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
            
                        <tr>
                            <th><label for="make-identifying">Make this an identifying membership </label></th>
                            <td><input type="checkbox" class="bpgc-checkbox" name="make-identifying[]" /></td>
                        </tr>
                    
                	<?php endif; ?>
                
            	<?php endif; ?>
            	</table>
            </div>
        </div>
            
		<input type="submit" name="save" value="Save" />

		<?php
		wp_nonce_field( 'groups_edit_save_' . $this->slug );
	}

	function edit_screen_save() {
		global $bp;

		if ( !isset( $_POST['save'] ) )
			return false;

		check_admin_referer( 'groups_edit_save_' . $this->slug );

		if ( !bpgc_add_existing_member_save() )
			bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . '/admin/' . $this->slug );
	}
	
	function widget_display() {}
}

/*Context class
 * 
 * this class will acquire the properties of the most specific user and group context, rather than
 * having to figure out which global variable to pick from. This should always have the right information
 * in it, and has functions that determine whether the user/group has certain privileges for this plugin.
 */
 
class User_Context{
	
	var $is_member;
	var $user_id;
	var $group_id;
	var $ig_button;
	var $component;
	var $is_admin;

	function user_context( $user_id = false, $group_id = false ){
		global $bp, $groups_template, $current_user, $identifying_group, $members_template, $member, $site_members_template, $group, $bp_unfiltered_uri;
		$this->component = $bp_unfiltered_uri[0];

		// find a group context
		if ( !$group_id ){
			if ( isset( $_COOKIE['bp_new_group_id'] ) && $bp->current_action == 'create' )
				$this->group = bpgc_get_group( $_COOKIE['bp_new_group_id'] );
			elseif ( $groups_template->group ) 
				$this->group =& $groups_template->group; 
			elseif ( $identifying_group )
				$this->group =& $identifying_group;
			elseif ( $bp->groups->current_group )
				$this->group = $bp->groups->current_group;
		} else {
			$this->group = bpgc_get_group( $group_id );
		}
		
		$this->group_id = $this->group->id;
		
		// find a user context
		if (!$user_id) {
			if ( $bp->displayed_user->id ){
				$this->user_id = $bp->displayed_user->id;
					
			} elseif ( $site_members_template->member->id ){
				$this->user_id = $site_members_template->member->id;
				
			} elseif ( $members_template->member->id ){
				$this->user_id = $members_template->member->id;
			
			//different names for user id? this should be fixed in the core ...	
			} elseif ( $members_template->member->user_id ){
				$this->user_id = $members_template->member->user_id;
					
			} elseif ( $member->user_id ){
				$this->user_id = $member->user_id;
				
			} else {
				$this->user_id = $current_user->id;
			}
		}
		
		$this->is_member = groups_is_user_member( $this->user_id, $this->group_id );
		$this->is_admin = groups_is_user_admin( $current_user->ID, $this->group_id );
		
		if ( $current_user->ID == $this->user_id )
			$this->ig_button = 'self';
		else if ( $this->user_can_assign_ig() )
			$this->ig_button = 'assign';

	}
	
	/** Member control conditional methods **/	
	
	/* type = 'new' or 'existing'
	 * TODO: Make bpgc_check_group_has_member_control a method here
	 */
	function user_can_add( $type ){
		global $bp, $current_user;
		
		if ( !bpgc_check_group_has_member_control( $this->group_id, $type ) )
			return false;
		
		if ( is_site_admin( $current_user->user_login ) || ( $this->is_admin && get_option('bpgc-group-admin-can-add-' . $type ) ) )
			return true;
	}
	
	function user_can_delete(){
		global $bp, $current_user;
		
		if ( ( get_option( 'bpgc-group-admin-can-delete' ) && $bp->is_item_admin ) || is_site_admin( $current_user->user_login ) )
			return true;
	}
	
	//only looks at $group->status
	function has_mc_enabled(){
		
		if ( ( get_option( 'bpgc-member-control-enable-public' ) && $this->group->status == 'public' ) || 
			( get_option( 'bpgc-member-control-enable-private' ) && $this->group->status == 'private' ) || 
			( get_option( 'bpgc-member-control-enable-public' ) && get_option( 'bpgc-member-control-enable-private' ) ) )
				return true;
	}

	/** Identifying group conditional methods **/
	
	function user_can_select_ig(){
		global $current_user;
		
		if ( get_option( 'bpgc-users-can-select-identifying' ) || is_site_admin( $current_user->user_login ) )
			return true;
	}
	
	function user_can_assign_ig(){
		global $bp, $current_user;
		
		if ( ( get_option( 'bpgc-group-admins-can-select-identifying' ) && $this->is_admin )
			 || is_site_admin( $current_user->user_login ) )
			 return true;
	}
	
	function has_ig_enabled(){
		 
		if ( !$this->group_id ){
			//print_r( $this->group );
			$this->group_id = get_usermeta( $this->user_id, 'bpgc_identifying' );
			$this->group = bpgc_get_group( $this->group_id );
		}
			
		if ( ( get_option( 'bpgc-identifying-enable-public' ) && $this->group->status == 'public' ) || 
			( get_option( 'bpgc-identifying-enable-private' ) && $this->group->status == 'private' ) || 
			( get_option( 'bpgc-identifying-enable-public' ) && get_option( 'bpgc-identifying-enable-private' ) ) ||
			( get_option( 'bpgc-identifying-enable-public' ) || get_option( 'bpgc-identifying-enable-private' ) && $this->component == BP_MEMBERS_SLUG ) )
				return true;
	}
}
