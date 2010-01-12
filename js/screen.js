jQuery(document).ready(function(){
	
	jQuery('#save-members').click(function(){
		addMembersAjax(postToAdd());
	});
	
	jQuery('#save-members-existing').click(function(){
		addMembersAjax(postToAddExisting());
	});
	
	jQuery("input[name='send-email[]']").change(function(){
		
		if ( this.checked ){
			jQuery("input[name='pass'], input[name='conf_pass'], input[name='send']").attr("disabled", "disabled");
			jQuery("label[for='pass'], label[for='conf_pass'], label[for='send']").css("opacity", .5)
		} else {
			jQuery("input[name='pass'], input[name='conf_pass'], input[name='send']").removeAttr("disabled");
			jQuery("label[for='pass'], label[for='conf_pass'], label[for='send']").css("opacity", 1)
		}
	});
	
	jQuery("input[name='send']").change(function(){
		
		if ( this.checked ){
			jQuery("input[name='send-email']").attr("disabled", "disabled");
			jQuery("label[for='send-email']").css("opacity", .5)
		} else {
			jQuery("input[name='send-email']").removeAttr("disabled");
			jQuery("label[for='send-email']").css("opacity", 1)
		}
	});
	
	jQuery("input[name='reassign[]']").change(function(){
		//alert("hi");										   
		if ( jQuery("input[name='reassign[]']:checked").val() == "reassign-to" )											   
			jQuery("select[name='reassign-select']").fadeIn();
		else
			jQuery("select[name='reassign-select']").fadeOut();
	});
	/*
	jQuery(".activity-list a").hover(
		function(){
			
			var isMember = new RegExp("members");
			var href = jQuery( this ).attr("href");
			var username = jQuery(this).html();
			var self = jQuery( this );
			noDisplay = false;
			
			if ( isMember.test( href ) ){
				doFetch = jQuery.post( ajaxurl, {
						action: 'bpgc_screen_fetch_identifying',
						'cookie': encodeURIComponent(document.cookie),
						'username': username
					},
					function(response)
					{	
						//alert( noDisplay );
						if ( !noDisplay )
							self.after("<a class='activity-identifying-group' href='#'>hi there</a>");
					});		
			}
		},
		function(){	
			var nextEl = jQuery( this ).next();
			
			noDisplay = true;
				
			if ( nextEl.hasClass('activity-identifying-group') )
				nextEl.remove();
		}
	);*/
	
});

function addMembersAjax(postObj) {
	jQuery('.bpgc-ajax-loader').toggle();
		jQuery('#save-members').attr({'disabled': true});
		
		jQuery.post( ajaxurl, postObj,
			function(response)
			{		
				jQuery('.bpgc-ajax-loader').toggle();
				jQuery('#message').remove();
				jQuery('#save-members').attr({'disabled': false});
				jQuery('#create-group-form').prepend(response);
			});
}

function postToAdd(){
	return {
		action: 'bpgc_create_screen_add_members_save',
		'cookie': encodeURIComponent(document.cookie),
		'_wpnonce': jQuery("input#_wpnonce-add-member-save").val(),
		'username': jQuery("input[name='username']").val(),
		'name': jQuery("input[name='name']").val(),
		'pass': jQuery("input[name='pass']").val(),
		'conf_pass': jQuery("input[name='conf_pass']").val(),
		'email': jQuery("input[name='email']").val(),
		'make-identifying': jQuery("input[name='make-identifying[]']").val(),
		'group-admin': jQuery("input[name='group-admin[]']").val(),
		'send-email': jQuery("input[name='send-email[]']").val(),
		'send': jQuery("input[name='send[]']").val()
	};
}

function postToAddExisting(){
	return {
		action: 'bpgc_create_screen_add_members_save',
		'existing': 1,
		'cookie': encodeURIComponent(document.cookie),
		'_wpnonce': jQuery("input#_wpnonce-add-member-save").val(),
		'username': jQuery("input[name='username']").val(),
		'make-identifying': jQuery("input[name='make-identifying[]']").val(),
		'group-admin': jQuery("input[name='group-admin[]']").val()
	};
}

function clearOptions(){
	jQuery("input[type='text']").val("");
	jQuery("input[type='checkbox']").checked("false");
}