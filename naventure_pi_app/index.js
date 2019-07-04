const request = require('request');
const fs = require('fs');
const checkInternetConnected = require('check-internet-connected');
const Gpio = require('onoff').Gpio;
const { exec } = require('child_process');

const snapButton = new Gpio(4, 'in', 'both'); // bouton snap photo sur GPIO pin 4
const blueLed = new Gpio(22, 'out');
const greenLed = new Gpio(24, 'out');

var pushed = 0; // statut du bouton

var mode = 1; // mode 0 = connecté à internet, mode 1 = deconnecté d'internet

const server = 'https://naventure.site';

greenLed.writeSync(1);

const Raspistill = require('node-raspistill').Raspistill;
const camera = new Raspistill({
	noFileSave: true,
    encoding: 'jpg',
    width: 640,
    height: 480
});

const image = '/home/pi/naventure_pi_app/image.jpg';
const serverPath = server+'/detect/';

/*
faire blinker la led bleue pendant qu'on verifie si y a une connection internet.
si pas de connection, on l'arrete, si y aune connection on l'allume
*/

var blinkInterval = setInterval( wait_connection_blink_Led, 250 );


const config = {
    timeout: 5000, //timeout
    retries: 5,//number of retries before failing
    domain: server
}

checkInternetConnected(config).then(() => {
    console.log("Internet ok");
    clearInterval(blinkInterval);
    blueLed.writeSync(1);
    mode = 0;
}).catch((error) => {
    console.log("Pas d'internet", error);
    clearInterval(blinkInterval);
    blueLed.writeSync(0);
    blueLed.unexport();
    mode = 1;
});

function wait_connection_blink_Led() {
    blueLed.writeSync(blueLed.readSync() === 0 ? 1 : 0);
}



function post_image( filepath ) {
    var blinkInterval = setInterval( wait_connection_blink_Led, 250 );
	fs.readFile( filepath, function(err, data) {
        if (err) throw err;
        request.post(
            { url:serverPath, formData:{ file:Buffer.from(data).toString('base64'), key:'066405491730069591562081375' } },
           // { url:serverPath, formData:{ file:fs.createReadStream( image ), key:'blabla' } },
            function (error, response, body) {
                if(error){
                    throw error;
                } else {
                    greenLed.writeSync(0);
                    
                    pushed = 0;
                    
                    // affiche à l'ecran => on envoie le array sous forme de tableau, par paires (deux par ligne) au python qui gère l'ecran
                    
                    clearInterval(blinkInterval);
    				blueLed.writeSync(1);
					
					var string = "";
                    
					body = '["Insect","Membrane-winged insect","Technology","Pest","Electronic device","Display device","Gadget","Invertebrate","Wasp"]';
					
					tmp_body = body.replace(/\s+/g, '');
					
					console.log(body);
					
                    if( tmp_body == "rienavoir" ){
                       string = '"Rien a voir !"';
                       
                    } else {
                        var pairs = splitPairs(JSON.parse(body));

                        for(var i = 0; i < pairs.length; i++){
                            string += '" ' + pairs[i][0] + '"," ' + pairs[i][1] + '" ';
                        }
                    }
                    //console.log(string);
                    exec("python /home/pi/RaspberryPi/python2/screen.py " + string, (error, stdout, stderr) => {
                      if (error) {
                        console.error(`exec error: ${error}`);
                        return;
                      }
                      console.log(`stdout: ${stdout}`);
                    });
                    
                    
                   // python /home/pi/RaspberryPi/python2/screen.py "Insect","Cricket" "tototittata","locuste" "abeille","mouche des boeufs" " bouzier"," grillon" " Pignouf"," lalala"
                    
                }
                
            }
        );
    });
}

snapButton.watch(function (err, value) { //bouton snap photo
    if (err) { //si erreur avec le bouton
        console.error('Une erreur est survenue avec le bouton snap gpio#4', err);
        return;
    } else { //si succès avec le bouton
        if( pushed ){ //si en train de poster la photo
            return false;   
        } else {
            if( value && !pushed ){ // si bouton value = 1 et pushed = 0
                greenLed.writeSync(1);
                console.error("pressed");
                pushed = 1;
				camera.takePhoto().then((image) => {
					fs.writeFile('/home/pi/naventure_pi_app/image.jpg', image, {encoding: 'binary'}, function (err) {
						if (err) {
							throw err;
						}

						console.log('saved photo ');
						post_image( '/home/pi/naventure_pi_app/image.jpg' );
						//greenLed.writeSync(0);
					});
				});
				
				
                
            }
        }
    }
});

var splitPairs = function(arr) {
    var pairs = [];
    for (var i=0 ; i<arr.length ; i+=2) {
        if (arr[i+1] !== undefined) {
            pairs.push ([arr[i], arr[i+1]]);
        } else {
            pairs.push ([arr[i]]);
        }
    }
    return pairs;
};

function unexportOnClose() { //function à executer quand on exit : remettre les boutons et les leds à zero
    snapButton.unexport();
    blueLed.unexport();
    greenLed.unexport();
};

process.on('SIGINT', unexportOnClose); //quand on quitte avec ctrl+c