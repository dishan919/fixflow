<?php

require_once "config/database.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare(
    "DELETE FROM issues WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$stmt->close();

header("Location: index.php");
exit;
?>