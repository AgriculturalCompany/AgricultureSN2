<?php
// Utilisation de PDO au lieu de mysqli pour une meilleure gestion des erreurs et plus de flexibilité
$servername = "172.30.3.19:3306";
$username = "administrateur";
$password = "company5682";
$dbname = "Agriculture";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définition du mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification des informations d'identification
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        // Le mot de passe ne doit pas être récupéré ou vérifié directement pour des raisons de sécurité
        // À la place, utilisez password_hash() lors de la création de l'utilisateur et password_verify() pour vérifier

        // Préparation de la requête SQL
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            // Utilisateur authentifié
            echo json_encode(array("success" => true, "message" => "Authentification réussie"));
        } else {
            // Informations d'identification incorrectes
            echo json_encode(array("success" => false, "message" => "Informations d'identification incorrectes"));
        }
    }
} catch(PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage();
}

// Pas besoin de fermer la connexion avec PDO, PHP s'en charge automatiquement à la fin du script
?>