<?php
/*
==================================================
USERS
==================================================
https://developer.wordpress.org/plugins/users/
https://developer.wordpress.org/plugins/users/working-with-users/

CHECKING USER INFO
username_exists()
email_exists()
wp_generate_password()

MANAGE USERS
wp_create_user()
wp_insert_user()
wp_update_user()
wp_delete_user()

USER META
get_user_meta()
add_user_meta()
update_user_meta()
delete_user_meta()

USER META (MULTISITE)
get_user_option()			// Gets user meta, searches first for the value stored for the current site, and if it doesn't exist then it uses the site-wide value
update_user_option()		// Updates the meta value for the user on the current site (update_user_meta uses site-wide data). If you pass 'true' as the 4th argument then it uses site-wide data
delete_user_option()		// same
