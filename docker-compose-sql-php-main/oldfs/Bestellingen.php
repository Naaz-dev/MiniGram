<?php
$servername = "mysql";
$username = "root";
$password = "password";
$database = "voorraadbeheer";

$conn = new mysqli($servername, $username, $password, $database);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $naam         = $_POST['naam'] ?? '';
  $type         = $_POST['type'] ?? '';
  $fabriek      = $_POST['fabriek'] ?? '';
  $aantal       = $_POST['aantal'] ?? 0;
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