=== Bp Group Control ===
Contributors: Dan Polant
Tags: buddypress, groups
Requires at least: BP 1.2
Tested up to: BP 1.2.4.1, WP 2.9

== Description ==

BP Group Control is a set of tools that gives group admins the ability to create new members for their groups, add existing users to their groups, directly delete members, and assign their members an identifying tag ( the group name ) that shows up next to their display name on their profile and directories. These features are supported by a rich options page that allows site admins to tweak the permission settings for these abilities.

Be careful with this plugin! Make sure you trust your group admins with the power you grant them.

You can choose to have different parts of this plugin active for public vs private groups as well.

== Installation ==

1. Upload `BP-Group-Control` to the `/wp-content/plugins/` directory
2. Move the 'bp-group-control' folder (it has a template file that you need) into buddypress/bp-themes
3. Activate the plugin
4. Take a look at the options page and configure the permissions the way you want

== Frequently Asked Questions ==

= Where is the settings page? =

Settings -> BP Group Control

= How do I make a group show up next to my name on the directory and on my home page? =

Go to the group homepage, or My Groups, and click "make identifying." You have to be a member of the group to do this.

= How do I add users directly to a group? =

Go to the group/manage members tab. To add a new user, click "add." To add an existing user, click "add existing." You can get to these screens when you are creating a new group as well.

= How do I change the email message that gets sent when I add some one? =

This functionality isn't really available, however, you can change the language of the message directly in the plugin at line 477 of bpgc-core.php. Remember that if you do this and then upgrade to the next version of bp-group-control (which will let you create your own template), it will erase your changes.

