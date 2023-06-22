/*** VARIABLES ***/
let form = document.getElementById("contact-form");

/*** FONCTIONS ***/
// Fonction de validation des noms et prénoms
function validateInput(input) {
    let regex = /^[a-zA-Z\s\-']*$/; // Autorise les lettres, les espaces, les tirets et les apostrophes
    return regex.test(input);
}
// Fonction de validation de l'adresse e-mail
function validateEmail(email) {
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
// Fonction de validation du formulaire
function validateForm() {
    // Récupérer les valeurs des champs du formulaire
    let nom = document.getElementById('nom').value;
    let prenom = document.getElementById('prenom').value;
    let mail = document.getElementById('mail').value;
    let file = document.getElementById("file").value;
    let description = document.getElementById('description').value;
    let errors = [];
    
    // Valider le nom et le prénom
    if (!validateInput(nom) || nom.length < 2 || nom.length > 255) {
        errors.push("Veuillez saisir un nom valide (2 à 255 caractères).");
        return false;
    }
    if (!validateInput(prenom) || prenom.length < 2 || prenom.length > 255) {
        errors.push("Veuillez saisir un prénom valide (2 à 255 caractères).");
        return false;
    }
    // Valider l'adresse e-mail
    if (!validateEmail(mail)) {
        errors.push("Veuillez saisir une adresse e-mail valide.");
        return false;
    }
    // Validation du fichier
    if (file !== "") {
        let allowedExtensions = ["jpg", "jpeg", "gif", "png"];
        let fileExtension = file.split(".").pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            errors.push("Veuillez sélectionner un fichier valide (JPG, JPEG, PNG, GIF).");
        }
    }
    // Valider la description
    if (description.length < 2 || description.length > 1000) {
        errors.push("Veuillez saisir une description valide (2 à 1000 caractères).");
        return false;
    }
    // Affichage des erreurs
    let errorContainer = document.getElementById("error-container");
    errorContainer.innerHTML = "";
  
    if (errors.length > 0) {
        let errorList = document.createElement("ul");
        errors.forEach(function (error) {
            let errorItem = document.createElement("li");
            errorItem.textContent = error;
            errorList.appendChild(errorItem);
        });
        errorContainer.appendChild(errorList);
        return false;
    }
    
    return true; // Le formulaire est valide
}

/*** ÉCOUTEURS D'ÉVÈNEMENT ***/
form.addEventListener("submit", function (event) {
    if(!validateForm()) {
        event.preventDefault();
    }
});