<?php
    session_start();
    include 'config.php';

    // Vérification et validation des données saisies
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Erreur CSRF : Action non autorisée !");
        }else{
            $errors = array();

            // Fonction de validation (nom, prénom)
            function validateName($input, $minLength, $maxLength)
            {
                $filteredInput = trim($input);
                if(strlen($filteredInput) >= $minLength && strlen($filteredInput) <= $maxLength && preg_match('/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/', $filteredInput)){
                    return filter_var($filteredInput, FILTER_SANITIZE_STRING);
                }else{
                    return false;
                }
            }

            // Validation du formulaire soumis
            if((isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['description'])) && (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mail']) && !empty($_POST['description']))){
                // Valider le nom
                $nom = validateName($_POST['nom'], 2, 255);
                if($nom === false){
                    $errors['nom'] = "Veuillez saisir un nom valide (2 à 255 caractères).";
                }

                // Valider le prénom
                $prenom = validateName($_POST['prenom'], 2, 255);
                if($prenom === false){
                    $errors['prenom'] = "Veuillez saisir un prénom valide (2 à 255 caractères).";
                }

                // Valider l'adresse e-mail
                if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) || !preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $_POST['mail'])){
                    $errors['mail'] = "Veuillez saisir une adresse e-mail valide.";
                }

                // Valider le fichier
                if(isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE){
                    if (is_uploaded_file($_FILES['file']['tmp_name']) && preg_match('/\.(jpg|jpeg|gif|png)$/', $_FILES['file']['name'])) {
                        $allowedExtensions = array('jpg', 'jpeg', 'gif', 'png');
                        $uploadedFileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                        if (!in_array($uploadedFileExtension, $allowedExtensions)) {
                            $errors['file'] = "Veuillez sélectionner un fichier valide (JPG, JPEG, PNG, GIF).";
                        }
                        $fileName = uniqid() . '.' . $uploadedFileExtension;
                        $destination = './files/' . $fileName;
                        move_uploaded_file($_FILES['file']['tmp_name'], $destination);
                    } else {
                        unset($_FILES['file']);
                        $errors['file'] = "Veuillez sélectionner un fichier valide (JPG, JPEG, PNG, GIF) et d'une taille maximale de 2 Mo.";
                    }
                }

                // Valider la description
                if(empty($_POST['description']) || strlen($_POST['description']) < 2 || strlen($_POST['description']) > 1000){
                    $errors['description'] = "Veuillez saisir une description valide (2 à 1000 caractères).";
                }

                // Vérification du captcha
                $captcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
                $secret_key = $recaptcha_secret_key;
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$captcha_response}");
                $response_data = json_decode($response);
                if(!$response_data->success){
                    $errors['captcha'] = "Veuillez valider le reCAPTCHA avant de continuer.";
                }

                // Redirection vers la page index.php si des erreurs sont présentes
                if(!empty($errors)){
                    $_SESSION['errors'] = $errors;
                    header('Location: index.php');
                    exit;
                }else{
                    // Si aucune erreur, enregistrement des données en base de données
                    $lastName = $nom;
                    $firstName = $prenom;
                    $mail = $_POST['mail'];
                    $description = $_POST['description'];
                    if($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE){
                        $file = $fileName;
                    }else{
                        $file = null;
                    }

                    // Enregistrement des données en base de données
                    $bdd = new PDO("mysql:host=localhost;dbname={$dbname};charset=utf8", $username, $password);
                    // $stm = $bdd->prepare("INSERT INTO contacts (lastName, firstName, mail, file, description) VALUES (?, ?, ?, ?, ?)");
                    // $stm->execute([$lastName, $firstName, $mail, $file, $description]);
                    $stm = $bdd->prepare("INSERT INTO contacts (lastName, firstName, mail, file, description) VALUES (:lastName, :firstName, :mail, :file, :description)");
                    $stm->bindParam(':lastName', $lastName);
                    $stm->bindParam(':firstName', $firstName);
                    $stm->bindParam(':mail', $mail);
                    $stm->bindParam(':file', $file);
                    $stm->bindParam(':description', $description);
                    $stm->execute();

                    // Envoie de l'email de confirmation de réception
                    // $to = $mail;
                    // $subject = "Confirmation de contact";
                    // $message = "Merci pour votre retour. Votre message a été reçu avec succès.";
                    // $headers = "From: Di Bernardo Nikko <dbnikko@outlook.fr>\r\n";
                    // $headers .= "Reply-To: Di Bernardo Nikko <dbnikko@outlook.fr>\r\n";
                    // $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

                    // // Envoi de l'email
                    // if (mail($to, $subject, $message, $headers)) {
                    //     echo "Email de confirmation envoyé avec succès à " . $to;
                    // } else {
                    //     echo "Erreur lors de l'envoi de l'email de confirmation.";
                    // }
                }
            }else{
                header("Location: index.php");
                exit();
            }
            // Suppression du jeton CSRF après son utilisation
    unset($_SESSION['csrf_token']);
        }
    }else{
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
    <title>Informations</title>
</head>
<body class="bg-gradient-to-b from-gray-800 to-gray-900 mx-2">
    <a href='./index.php' class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Retour au formulaire</a>
    <?php
    if(empty($errors)){
        // Affichage des informations soumises
        echo "<h1 class='text-2xl flex justify-center text-center mt-1 text-white'>Informations soumises :</h1>";
        echo "<p class='text-s text-white text-center mt-2'><strong class='text-white dark:text-gray-200'>Last Name:</strong> " . htmlspecialchars($lastName) . "</p>";
        echo "<p class='text-s text-white text-center mt-2'><strong class='text-white dark:text-gray-200'>First Name:</strong> " . htmlspecialchars($firstName) . "</p>";
        echo "<p class='text-s text-white text-center mt-2'><strong class='text-white dark:text-gray-200'>Email:</strong> " . htmlspecialchars($mail) . "</p>";
        echo "<p class='text-s text-white text-center mt-2'><strong class='text-white dark:text-gray-200'>Description:</strong> " . htmlspecialchars($description) . "</p>";
        if(!empty($file)){
            echo "<h2 class='text-xl flex justify-center text-center mt-1 text-white'>Image soumise :</h2>";
            echo "<img src='files/$file' alt='Image'>";
        }
    }
    ?>
</body>
</html>