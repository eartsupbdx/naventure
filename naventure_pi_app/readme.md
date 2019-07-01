L'application nodejs envoie les images faites par la caméra en base64 via POST à php, qui soumet l'image à Google vision API

Exemple :

![](https://github.com/eartsupbdx/naventure/blob/master/naventure_pi_app/pics/locusta.jpg?raw=true)

Google vision API répond par un grand tableau, trié par probabilité, que nous nettoyons, pour renvoyer un JSON à l'application node sous cette forme :
["Insect","Invertebrate","Cricket-like insect","Locust","Cricket","Arthropod","Pest","Grasshopper","Organism","Chapulines"]
