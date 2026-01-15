<?php
$servername = "mysql";
$username = "root";
$password = "password";
$database = "voorraadbeheer";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Locatiefilter ophalen
$locatieFilter = isset($_GET['locatieid']) && $_GET['locatieid'] !== '' ? intval($_GET['locatieid']) : null;

// Filterformulier tonen
echo "<form method='get' action=''>
        <label for='locatieid'>Filter op locatie:</label>
        <select name='locatieid' onchange='this.form.submit()'>
          <option value=''>-- Alle locaties --</option>
          <option value='1'" . ($locatieFilter === 1 ? " selected" : "") . ">Rotterdam</option>
          <option value='2'" . ($locatieFilter === 2 ? " selected" : "") . ">Almere</option>
          <option value='3'" . ($locatieFilter === 3 ? " selected" : "") . ">Eindhoven</option>
        </select>
      </form><br>";

// SQL-query op basis van filter
if ($locatieFilter) {
  $sql = "SELECT productid, naam, type, fabriek, aantal, inkoopprijs, verkoopprijs, locatieid 
          FROM product 
          WHERE locatieid = $locatieFilter 
          ORDER BY locatieid";
} else {
  $sql = "SELECT productid, naam, type, fabriek, aantal, inkoopprijs, verkoopprijs, locatieid 
          FROM product 
          ORDER BY locatieid";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table border='1' class='center'>
          <tr>
            <th>Product</th>
            <th>Type</th>
            <th>Fabriek</th>
            <th>Aantal</th>
            <th>Inkoopprijs</th>
            <th>Verkoopprijs</th>
            <th>Acties</th>
          </tr>";

  $current_locatie = null;

  while ($row = $result->fetch_assoc()) {
    if ($locatieFilter === null && $current_locatie !== $row['locatieid']) {
      $current_locatie = $row['locatieid'];
      $locatieNaam = $current_locatie == 1 ? "Rotterdam" : ($current_locatie == 2 ? "Almere" : "Eindhoven");
      echo "<tr><th colspan='7'>{$locatieNaam}</th></tr>";
    }

    echo "<tr>
        <form action='opslaan.php' method='post'>
          <td>{$row['naam']}</td>
          <td>{$row['type']}</td>
          <td>{$row['fabriek']}</td>
          <td>{$row['aantal']}</td>
          <td><input type='number' step='0.01' name='inkoopprijs' value='{$row['inkoopprijs']}'></td>
          <td><input type='number' step='0.01' name='verkoopprijs' value='{$row['verkoopprijs']}'></td>
          <td>
            <input type='hidden' name='productid' value='{$row['productid']}'>
            <button type='submit'>Opslaan</button>
        </form>
        <form action='verwijderen.php' method='post' style='display:inline;'>
            <input type='hidden' name='productid' value='{$row['productid']}'>
            <button type='submit' onclick='return confirm(\"Weet je zeker dat je dit product wilt verwijderen?\")'>Verwijderen</button>
        </form>
        <form action='bestellenpagina.php' method='get' style='display:inline;'>
            <input type='hidden' name='productid' value='{$row['productid']}'>
            <button type='submit'>Bestellen</button>
        </form>
          </td>
      </tr>";
  }

  echo "</table>";
} else {
  echo "Geen producten gevonden.";
}

$conn->close();
?>