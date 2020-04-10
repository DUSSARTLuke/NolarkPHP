/* 
 * Page JS créé dans le cadre du cours de web Dev le 16/03/2020
 * Auteur : Luke DUSSART
 * Email : lukedussart@hotmail.fr
 */

function validEnvoi() {
    if (window.document.querySelector("#i_nom").value === "" &&
            window.document.querySelector("#i_prenom").value === "") {
        alert("Le nom ou le prénom doivent être renseignés"); // On affiche un message
    } else if (window.document.querySelector("#i_email").value === "") {
        alert("L'email doit être rempli"); // On affiche un message
    } else {
        let question = "Souhaitez-vous réellement utiliser l'adresse suivante : "
                + window.document.querySelector("#i_email").value;
        if (confirm(question)) {
            window.document.querySelector("#form_contact").submit(); // OK envoyer
        }

    }
}
window.addEventListener("load", function () {
    window.document.querySelector("#btn_envoyer").addEventListener("click", validEnvoi);
});




