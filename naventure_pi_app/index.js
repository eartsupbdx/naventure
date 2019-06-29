const request = require('request');
const fs = require('fs');
const checkInternetConnected = require('check-internet-connected');
const Gpio = require('onoff').Gpio;

const pushButton = new Gpio(4, 'in', 'both'); // bouton snap photo sur GPIO pin 4
const blueLed = new Gpio(18, 'out');

var pushed = 0; // statut du bouton

var mode = 1; // mode 0 = connecté à internet, mode 1 = deconnecté d'internet

const server = '192.168.0.47';

const image = './pics/locusta.jpg';
const serverPath = 'http://'+server+'/naventure/naventure.site/htdocs/detect.php';

const config = {
    timeout: 5000, //timeout connecting to each server(A and AAAA), each try (default 5000)
    retries: 5,//number of retries to do before failing (default 5)
    domain: server
}

checkInternetConnected(config).then(() => {
    console.log("Internet ok");
    blueLed.writeSync(1);
    mode = 0;
}).catch((error) => {
    console.log("Pas d'internet", error);
    blueLed.writeSync(0);
    mode = 1;
});



function post_image( filepath ) {
    fs.readFile( filepath, function(err, data) {
        if (err) throw err;
        request.post(
            { url:serverPath, formData:{ file:Buffer.from(data).toString('base64')} },
            function (error, response, body) {
                console.log(body);
                pushed = 0;
            }
        );
    });
}

pushButton.watch(function (err, value) { //gestion du bouton snap photo
  
    if (err) { //si erreur avec le bouton
        console.error('Une erreur est survenue avec le bouton snap gpio#4', err);
        return;
    } else { //si succès avec le bouton
        if( pushed ){ //si en train de poster la photo
            return false;   
        } else {
            if( value && !pushed ){ // si bouton value = 1 et pushed = 0
                console.error("pressed");
                pushed = 1;
                post_image( image );
            }
        }
    }
});

function unexportOnClose() { //function à executer quand on exit
    pushButton.unexport(); // unexport GPIO boutton pour libérer les ressources
    blueLed.unexport(); // unexport GPIO LED pour libérer les ressources
};

process.on('SIGINT', unexportOnClose); //function à executer quand on quitte avec ctrl+c