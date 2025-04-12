<?php
function renderTable($data, $headers = null) {
    if (empty($data) || !is_array($data)) {
        echo "No hay datos para mostrar.<br>";
        return;
    }

    echo "<table border='1' cellpadding='5' cellspacing='0'>";

    // Encabezados automáticos si no se pasan
    if ($headers === null) {
        $headers = array_keys($data[0]);
    }

    echo "<tr>";
    foreach ($headers as $header) {
        echo "<th>" . htmlspecialchars(ucwords(str_replace('_', ' ', $header))) . "</th>";
    }
    echo "</tr>";

    foreach ($data as $row) {
        echo "<tr>";
        foreach ($headers as $field) {
            echo "<td>" . htmlspecialchars($row[$field]) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}
