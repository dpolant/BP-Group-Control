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
            
            <div class="bp-widget">
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

                
                
                    <?php global $bp;
                    
                    $group = $bp->groups->current_group;
                    
                    if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ) : ?>
                    
                        <?php
                        
                        if ( ( is_site_admin() && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->is_item_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
                    
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
            
            <div class="bp-widget">
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
                
                    <?php global $bp;
                    
                    $group = $bp->groups->current_group;
                    
                    if ( ( get_option( 'bpgc-identifying-enable-public' ) && $group->status == 'public' ) || ( get_option( 'bpgc-identifying-enable-private' ) && $group->status == 'private' ) ) : ?>
                    
                        <?php
                        
                        if ( ( is_site_admin() && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->is_item_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
                    
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
            
             <div class="bp-widget">
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
				
					if ( ( is_site_admin() && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->is_item_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
            
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
            
             <div class="bp-widget">
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
				
					if ( ( is_site_admin() && get_option('bpgc-site-admins-can-select-identifying') ) || ( $bp->is_item_admin && get_option('bpgc-group-admins-can-select-identifying') )) : ?>
            
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
