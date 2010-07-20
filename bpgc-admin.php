<div class="wrap">
		
	<h2><?php _e( 'BP-Groups-Control Settings', 'bp-groups-control' ) ?></h2>
            
    <form action="options.php" method="post" id="bpgc-admin-form">
    
    <?php settings_fields( 'bpgc-admin' );?>
    
    	<h3>Active Components</h3>
         <fieldset>
            <table class="form-table">
                <tbody>
                	<th></th>
                    <th>Private groups</th>
                    <th>Public groups</th>
                    <tr>
                        <th scope="row"><?php _e( 'Identifying groups', 'bp-groups-control' ) ?>:</th>
                        <td>
                            <input type="checkbox" name="bpgc-identifying-enable-private[]" <?php if ( get_option('bpgc-identifying-enable-private') ) : ?>checked='checked' <?php endif; ?>/>
                        </td>
                        <td>
                            <input type="checkbox" name="bpgc-identifying-enable-public[]" <?php if ( get_option('bpgc-identifying-enable-public') ) : ?>checked='checked' <?php endif; ?>/>
                        </td>				
                    </tr>
                    <tr>
                        <th scope="row"><?php _e( 'Direct member control', 'bp-groups-control' ) ?>:</th>
                        <td>
                            <input type="checkbox" name="bpgc-member-control-enable-private[]" <?php if ( get_option('bpgc-member-control-enable-private') ) : ?>checked='checked' <?php endif; ?>/>
                        </td>
                        <td>
                            <input type="checkbox" name="bpgc-member-control-enable-public[]" <?php if ( get_option('bpgc-member-control-enable-public') ) : ?>checked='checked' <?php endif; ?>/>
                        </td>				
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <h3>Direct member control settings</h3>
        <p>With BP-Groups-Control, you can allow site admins and/or group admins to add members to their groups without making the users do anything</p>
        	<h4><?php _e( 'Who should be able to add existing user to their groups?', 'bp-groups-control' ) ?></h4>
            <fieldset>
            
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Site admins', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="bpgc-site-admin-can-add-existing[]" <?php if ( get_option('bpgc-site-admin-can-add-existing') ) : ?> checked='checked' <?php endif; ?>/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Group admins', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="bpgc-group-admin-can-add-existing[]" <?php if ( get_option('bpgc-group-admin-can-add-existing') ) :?>checked='checked' <?php endif; ?> />
                                <label for="bpgc-group-admin-can-add-existing"><strong>Important!</strong> <?php _e( 'Can you trust your group admins?', 'bp-groups-control' ) ?></label>
                            </td>	
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Group admins of user-created groups', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="user-groups-have-member-control-existing[]" <?php if ( get_option('user-groups-have-member-control-existing') ) : ?>checked='checked' <?php endif; ?>/>
                                <label for="user-groups-have-member-control-existing"><?php _e( 'Be prudent with this one too.' ) ?></label>
                            </td>	
                        </tr>
                     </tbody
                 ></table>
            </fieldset>
            <h4><?php _e( 'Who should be able to add new users to their groups and to the site?', 'bp-groups-control' ) ?></h4>
            	<fieldset>
                
                 <table class="form-table">
                 	<tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Site admins', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="bpgc-site-admin-can-add-new[]" <?php if ( get_option('bpgc-site-admin-can-add-new') ) : ?>checked='checked' <?php endif; ?>/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Group admins', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="bpgc-group-admin-can-add-new[]" <?php if ( get_option('bpgc-group-admin-can-add-new') ) : ?>checked='checked' <?php endif; ?>/>
                                <label for="bpgc-group-admin-can-add-new"><strong>Important!</strong> <?php _e( 'This means group admins will be able to add any new user they want. Can you trust your group admins with this power?', 'bp-groups-control' ) ?></label>
                            </td>	
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Group admins of user-created groups', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="user-groups-have-member-control-new[]" <?php if ( get_option('user-groups-have-member-control-new') ) : ?>checked='checked' <?php endif; ?>/>
                                <label for="user-groups-have-member-control-new"><?php _e( 'Be prudent with this one too.' ) ?></label>
                            </td>	
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <h4><?php _e( 'Besides site admins, who should be able to create new groups?', 'bp-groups-control' ) ?></h4>
            	<fieldset>
                
                 <table class="form-table">
                 	<tbody>
                        <tr>
                            <th scope="row"><?php _e( 'All other users', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name="bpgc-users-can-create-groups[]" <?php if ( get_option('bpgc-users-can-create-groups') ) : ?>checked='checked' <?php endif; ?>/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
                        <h4><?php _e( 'Allow group admins to delete member accounts that they create?', 'bp-groups-control' ) ?></h4>
            	<fieldset>
                
                 <table class="form-table">
                 	<tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Yes', 'bp-groups-control' ) ?>:</th>
                            <td>
                                <input type="checkbox" name='bpgc-group-admin-can-delete[]' <?php if ( get_option('bpgc-group-admin-can-delete') ) : ?>checked='checked' <?php endif; ?>/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <h3>Identifying group settings</h3>
            <p>Members can choose to have one group appear as an identifying tag on their profile.</p>
            <h4>Customize identifying text/style/layout</h4>
            <fieldset>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e( 'Text on profile before identifying group line', 'bp-groups-control' ) ?>:</th>
                        <td>
                            <input type="text" name="bpgc-text-before-identifying" value="<?php echo get_option('bpgc-text-before-identifying') ?>" style="width:258px;"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            </fieldset>
            <h4>Identifying group permissions</h4>
            <fieldset>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Allow users to select an identifying group?', 'bp-groups-control' ) ?></th>
                            <td>
                                <input type="checkbox" name="bpgc-users-can-select-identifying[]" <?php if ( get_option('bpgc-users-can-select-identifying') ) : ?>checked='checked' <?php endif; ?>/>
                            </td>	
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Allow site admins to select identifying groups for users?', 'bp-groups-control' ) ?></th>
                            <td>
                                <input type="checkbox" name="bpgc-site-admins-can-select-identifying[]" <?php if ( get_option('bpgc-site-admins-can-select-identifying') ) : ?>checked='checked' <?php endif; ?>/>
                            </td>	
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Allow group admins to select identifying groups for their group members?', 'bp-groups-control' ) ?></th>
                            <td>
                                <input type="checkbox" name="bpgc-group-admins-can-select-identifying[]" <?php if ( get_option('bpgc-group-admins-can-select-identifying') ) : ?>checked='checked' <?php endif; ?>"/>
                            </td>	
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            
            <p class="submit">
				<input class="button-primary" type="submit" value="<?php _e( 'Save Settings', 'buddypress' ) ?>"/>
			</p>
    </form>   
</div>