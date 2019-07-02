<?php
/*
Template Name: detection image
*/

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use Google\Cloud\Vision\VisionClient;

if( isset( $_POST ) ){
    //
    if( $_POST['key'] ){
        
        if( isset( $_POST["file"] ) ){
            
            //var_dump($_POST);
            
            //die();
            
            // authentifier sur google cloud avec la clé .json
            $vision = new VisionClient([
                'keyFilePath' => '../naventure-790ac901e712.json'
            ]);

            // detecter LABEL_DETECTION cf. https://cloud.google.com/vision/docs/detecting-labels
            $image = $vision->image(
                base64_decode($_POST["file"]), // décoder la chaîne base64 envoyée
                //file_get_contents($_POST["file"]),
                //fopen('./images/grub.jpg', 'r'),
                ['LABEL_DETECTION']
            );

            $annotation = $vision->annotate($image);
            //print_r( $annotation );

            $quoica = [];
            $insecteouplante = false;

            foreach ($annotation->info() as $key => $img) {
                foreach( $img as $value ){
                    $quoica[] = $value['description'];
                    // si c'est une plante ou un insecte on pourra renvoyer un array
                    if ( in_array("Insect", $value) || in_array("Plant", $value) ) {
                        $insecteouplante = true;
                    }
                }
            }

            if( $insecteouplante ){
                echo json_encode( $quoica );
                
                // enregistrer l'image dans le profil du user
                // user avec cette api key
                $users = new WP_User_Query( array( 'meta_key' => 'api_key', 'meta_value' => htmlspecialchars($_POST["key"]) ) );
                $user = $users->results[0]->data;
                $user_ID = $user->ID;

                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );

                $base64_img = $_POST["file"];

                $upload_dir  = wp_upload_dir();
                $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

                $img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
                $img             = str_replace( ' ', '+', $img );
                $decoded         = base64_decode( $img );
                $filename        = $title . '.jpg';
                $file_type       = 'image/jpeg';
                $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;


                $upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

                

                $attachment = array(
                    'post_mime_type' => $file_type,
                    'post_title'     => implode(", ", $quoica), //preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                    'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
                );

                $attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
                // mettre le user comme proprietaire de l'image
                $my_post = array(
                  'ID' => $attach_id,
                  'post_author' => $user_ID
                );
                wp_update_post( $my_post );
                //echo "image ok";
                
                //
                
            } else {
                echo 'rienavoir';
            }

        }
        
    } else {
        echo 'echec';
    }

}
?>  