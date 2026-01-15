<?php
$servername = "mysql";
$username = "root";
$password = "password";
$database = "voorraadbeheer";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $naam         = $_POST['naam'] ?? '';
  $type         = $_POST['type'] ?? '';
  $fabriek      = $_POST['fabriek'] ?? '';
  $aantal       = $_POST['aantal'] ?? 0;
  $prijs        = $_POST['prijs'] ?? 0.0;
  $inkoopprijs  = $_POST['inkoopprijs'] ?? 0.0;
  $verkoopprijs = $_POST['verkoopprijs'] ?? 0.0;
  $locatieid    = $_POST['locatieid'] ?? null;

  $stmt = $conn->prepare("INSERT INTO product (productid, naam, type, fabriek, aantal, prijs, inkoopprijs, verkoopprijs, locatieid)
                          VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssidddi", $naam, $type, $fabriek, $aantal, $prijs, $inkoopprijs, $verkoopprijs, $locatieid);

 if ($stmt->execute()) {
header("Location: index.php?message=product_toegevoegd");

  exit();
} else {
  echo "Fout bij toevoegen: " . $stmt->error;
}


  $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voeg Product Toe</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h2>Nieuw Product Toevoegen</h2>
  <a href="index.php">voorraad</a>
  <form action="insertproduct.php" method="post">


    <label for="naam">Naam:</label>
    <input type="text" name="naam" required><br>

    <label for="type">Type:</label>
    <input type="text" name="type" required><br>

    <label for="fabriek">Fabriek:</label>
    <input type="text" name="fabriek" required><br>

    <label for="aantal">Aantal:</label>
    <input type="number" name="aantal" required><br>

    <label for="prijs">Prijs:</label>
    <input type="number" step="0.01" name="prijs" required><br>

    <label for="inkoopprijs">Inkoopprijs:</label>
    <input type="number" step="0.01" name="inkoopprijs" required><br>

    <label for="verkoopprijs">Verkoopprijs:</label>
    <input type="number" step="0.01" name="verkoopprijs" required><br>

    <label for="locatieid">Locatie ID:</label>
    <select name="locatieid" required>
      <option value="1">Rotterdam</option>
      <option value="2">Almere</option>
      <option value="3">Eindhoven</option>
    </select><br><br>

    <input type="submit" value="Toevoegen">
  </form>
</body>
</html>