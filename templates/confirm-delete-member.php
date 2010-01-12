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
				
                <p><?php bpgc_the_delete_members_confirm_button() ?></p>
                
                <?php do_action( 'bp_after_group_admin_content' ) ?>
		</div>

	<?php endwhile; endif; ?>

<?php get_footer() ?>	