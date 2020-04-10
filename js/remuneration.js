/* 
 * Page JS créé dans le cadre du cours de web Dev le 23/03/2020
 * Auteur : Luke DUSSART
 * Email : lukedussart@hotmail.fr
 */

function prime(){
    const fixe = 1100;
    const comS20 = 0.02;
    const comXspi = 0.06;
    let prime=0;
    let nbS20 = parseInt(window.document.querySelector("#i_nbreS20").value);
    let nbXspi = parseInt(window.document.querySelector("#i_nbreXspi").value);
    let nbMulti = parseInt(window.document.querySelector("#i_nbreMulti").value);
    for (let i=0; i<nbS20; i++){
        prime += fixe*comS20;
    }
    if (nbXspi >= 50){
        for(let i=50; i<nbXspi; i++){
            prime += fixe*comXspi;
        }
    }
    if (nbMulti <=20){
        for(let i=50; i<nbMulti; i++){
            prime += fixe*0.04;
        }
    }
    else if (nbMulti >20 && nbMulti <=50){
        for(let i=50; i<nbMulti; i++){
            prime += fixe*0.06;
        }
    }
    else if (nbMulti> 50){
        for(let i=50; i<nbMulti; i++){
            prime += fixe*0.1;
        }
    }
    else {
        alert("Le nombre de casque n'est pas correctement écrit");
    }
    return prime;
}
function anciennete(){
    const fixe = 1100;
    const gain = prime;
    let nbAncien = parseInt(window.document.querySelector("#num_ancien").value);
    if(nbAncien < 5){
        fixe += gain;
    } 
    
}
