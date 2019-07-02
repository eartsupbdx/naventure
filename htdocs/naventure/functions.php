<?php

// custom login page
function login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo_login.jpg);
		height:220px;
		width:300px;
		background-size: 300px 220px;
		background-repeat: no-repeat;
        	padding-bottom: 30px;
        }
		body,html{
			background-color:#fff!important;
		}
		#loginform{
			box-shadow:none;
			padding:0;
			margin:0;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'login_logo' );
// fin custom login page

// rediriger users vers le front apres login //
add_action( 'wp_login', 'redirect_based_on_roles', 10, 2);
function redirect_based_on_roles($user_login, $user) {

    if( in_array( 'subscriber',$user->roles ) ){
        exit( wp_redirect('/' ) );
    }   
}

// rediriger users vers le front apres login //
add_action('admin_init', 'restrict_access_administration');
function restrict_access_administration(){
    if ( current_user_can('subscriber') ) {
       exit(wp_redirect( '/') );
    }
}

// générer l'api KEY quand le user s'inscrit
add_action( 'user_register', 'generate_api_key', 10, 1 );
function generate_api_key( $user_id ) {
	$api_key = '';
    $seed1 = rand(0, 9999);
	$seed2 = rand(0, 9999);
	$api_key = str_replace(' ', $seed1, microtime());
	$api_key = str_replace('.', $seed2, $api_key);
	update_user_meta( $user_id, 'api_key', $api_key);
}

// back office : afficher les champs custom dans les profils
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
function extra_user_profile_fields( $user ) { ?>
    <table class="form-table">
    <tr>
        <th><label for="api_key">API KEY</label></th>
        <td>
            <input type="text" name="api_key" id="api_key" value="<?php echo esc_attr( get_the_author_meta( 'api_key', $user->ID ) ); ?>" class="regular-text" />
        </td>
    </tr>
    </table>
<?php }
// back office : sauvegarder les champs custom dans les profils
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'api_key', $_POST['api_key'] );
}


?>