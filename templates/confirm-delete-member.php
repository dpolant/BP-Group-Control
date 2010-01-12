<?php get_header() ?>

	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

		<div class="content-header">
			<ul class="content-header-nav">
				<?php bp_group_admin_tabs(); ?>
			</ul>
		</div>

		<div id="content">	

			<?php do_action( 'template_notices' ) // (error/success feedback) ?>

            <?php do_action( 'bp_before_group_admin_content' ) ?>
            
            <h2>Delete user account</h2>
            
            <div id="message" class="info">
                <p>WARNING: Deleting this user will completely erase him/her from the site</p>
            </div>
            <form action="<?php bpgc_the_delete_members_confirm_action() ?>" method="post">
            <p>What would you like to do with this user's posts?</p>
            
            <p>
                <label>Delete them</label><input name="reassign" value="delete" type="radio"/><br/>
                
                <?php if ( is_site_admin() ) : ?>
                    <label>Reassign to:</label><input name="reassign" value="reassign-to" type="radio" checked="checked"/>
                    <?php bpgc_the_delete_member_dropdown() ?>
                <?php else : ?>
                    <label>Reassign to me</label><input name="reassign" value="reassign-me" type="radio" checked="checked"/><br/>
                <?php endif; ?>
            </p>
            
            <input type="checkbox" name="delete-member-understand" id="delete-member-understand" value="1" onclick="if(this.checked) { document.getElementById('bpgc-delete-confirm').disabled = ''; } else { document.getElementById('bpgc-delete-confirm').disabled = 'disabled'; }" /> <?php _e( 'I understand the consequences of deleting this user account.', 'buddypress' ); ?>
            
            <p><input type="submit" id="bpgc-delete-confirm" title="<?php _e( 'Delete user account', 'bp-group-control' ); ?>" value ="<?php _e( 'Delete user account', 'bp-group-control' ); ?>" disabled="disabled" /></p>
            <input type="hidden" name="user-id" id="user-id" value="<?php bpgc_the_delete_user_id() ?>" />
            </form>
            
            <?php do_action( 'bp_after_group_admin_content' ) ?>
		</div>

	<?php endwhile; endif; ?>

<?php get_footer() ?>	