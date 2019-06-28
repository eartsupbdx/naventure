<img src="images/locusta.jpg"/><br/>
<?php
require '../vendor/autoload.php';

use Google\Cloud\Vision\VisionClient;

// authentifier sur google cloud avec un .json
$vision = new VisionClient([
    'keyFilePath' => '../naventure-790ac901e712.json'
]);

// detecter LABEL_DETECTION cf. https://cloud.google.com/vision/docs/detecting-labels
$image = $vision->image(
    fopen('./images/locusta.jpg', 'r'),
    ['LABEL_DETECTION']
);

$annotation = $vision->annotate($image);
$quoica = [];
$insecteouplante = false;

foreach ($annotation->info() as $key => $img) {
    foreach( $img as $value ){
        $quoica[] = $value['description'];
        // si c'est une plante ou un insecte
        if ( in_array("Insect", $value) || in_array("Plant", $value) ) {
            $insecteouplante = true;
        }
    }
}

if( $insecteouplante ){
    echo json_encode( $quoica );
} else {
    echo 'rienavoir';
}

//print_r( $annotation );

?>