<?php
require_once "config/database.php";

$search = trim($_GET["search"] ?? "");
$status = trim($_GET["status"] ?? "");

$sql = "SELECT * FROM issues WHERE 1=1";

$params = [];
$types = "";

// Search in all useful issue fields
if ($search !== "") {

    $sql .= " AND (
        title LIKE ?
        OR description LIKE ?
        OR category LIKE ?
        OR location LIKE ?
        OR priority LIKE ?
        OR status LIKE ?
        OR reported_by LIKE ?
    )";

    $term = "%" . $search . "%";

    $params[] = $term; // title
    $params[] = $term; // description
    $params[] = $term; // category
    $params[] = $term; // location
    $params[] = $term; // priority
    $params[] = $term; // status
    $params[] = $term; // reported_by

    $types .= "sssssss";
}

// Status dropdown filter
if ($status !== "") {

    $sql .= " AND status = ?";

    $params[] = $status;
    $types .= "s";
}

// Newest issues first
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query error: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$result = $stmt->get_result();


// Dashboard counts
$totalResult = $conn->query(
    "SELECT COUNT(*) AS total FROM issues"
);

$total = $totalResult->fetch_assoc()["total"];


$reportedResult = $conn->query(
    "SELECT COUNT(*) AS total
     FROM issues
     WHERE status = 'Reported'"
);

$reported = $reportedResult->fetch_assoc()["total"];


$progressResult = $conn->query(
    "SELECT COUNT(*) AS total
     FROM issues
     WHERE status = 'In Progress'"
);

$progress = $progressResult->fetch_assoc()["total"];


$resolvedResult = $conn->query(
    "SELECT COUNT(*) AS total
     FROM issues
     WHERE status = 'Resolved'"
);

$resolved = $resolvedResult->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FixFlow Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header class="header">

    <div>
        <h1>FixFlow</h1>
        <p>Community Repair & Maintenance Tracker</p>
    </div>

    <a href="create.php" class="btn">
        + Report Issue
    </a>

</header>

<main class="container">

    <section class="cards">

        <div class="card">
            <h3>Total Issues</h3>
            <strong><?php echo $total; ?></strong>
        </div>

        <div class="card">
            <h3>Reported</h3>
            <strong><?php echo $reported; ?></strong>
        </div>

        <div class="card">
            <h3>In Progress</h3>
            <strong><?php echo $progress; ?></strong>
        </div>

        <div class="card">
            <h3>Resolved</h3>
            <strong><?php echo $resolved; ?></strong>
        </div>

    </section>


    <section class="panel">

        <div class="panel-header">

            <h2>Maintenance Issues</h2>

            <form method="GET" class="filters">

                <input
                    type="text"
                    name="search"
                    placeholder="Search issues..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >

                <select name="status">

                    <option value="">All Status</option>

                    <option value="Reported"
                        <?php echo $status === "Reported" ? "selected" : ""; ?>>
                        Reported
                    </option>

                    <option value="In Progress"
                        <?php echo $status === "In Progress" ? "selected" : ""; ?>>
                        In Progress
                    </option>

                    <option value="Resolved"
                        <?php echo $status === "Resolved" ? "selected" : ""; ?>>
                        Resolved
                    </option>

                </select>

                <button type="submit" class="btn">
                    Search
                </button>

                <a href="index.php" class="btn secondary">
                    Reset
                </a>

            </form>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Issue</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Reported By</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                <?php if ($result->num_rows > 0): ?>

                    <?php while ($issue = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                #<?php echo $issue["id"]; ?>
                            </td>

                            <td>

                                <strong>
                                    <?php echo htmlspecialchars($issue["title"]); ?>
                                </strong>

                                <small>
                                    <?php echo htmlspecialchars($issue["description"]); ?>
                                </small>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($issue["category"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($issue["location"]); ?>
                            </td>

                            <td>
                                <span class="badge">
                                    <?php echo htmlspecialchars($issue["priority"]); ?>
                                </span>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($issue["status"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($issue["reported_by"]); ?>
                            </td>

                            <td class="actions">

                                <a
                                    href="edit.php?id=<?php echo $issue["id"]; ?>"
                                    class="edit"
                                >
                                    Edit
                                </a>

                                <a
                                    href="delete.php?id=<?php echo $issue["id"]; ?>"
                                    class="delete"
                                    onclick="return confirmDelete();"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="empty">
                            No issues found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

<script src="assets/js/app.js"></script>

</body>
</html>