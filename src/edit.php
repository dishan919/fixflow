<?php

require_once "config/database.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid issue ID.");
}

$stmt = $conn->prepare(
    "SELECT * FROM issues WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$issue = $result->fetch_assoc();

if (!$issue) {
    die("Issue not found.");
}


$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $priority = $_POST["priority"] ?? "Medium";
    $status = $_POST["status"] ?? "Reported";
    $reported_by = trim($_POST["reported_by"] ?? "");

    if (
        $title === "" ||
        $description === "" ||
        $category === "" ||
        $location === "" ||
        $reported_by === ""
    ) {

        $error = "Please complete all required fields.";

    } else {

        $sql = "UPDATE issues
                SET
                    title = ?,
                    description = ?,
                    category = ?,
                    location = ?,
                    priority = ?,
                    status = ?,
                    reported_by = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssssi",
            $title,
            $description,
            $category,
            $location,
            $priority,
            $status,
            $reported_by,
            $id
        );

        if ($stmt->execute()) {

            header("Location: index.php");
            exit;

        } else {

            $error = "Unable to update the issue.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Issue - FixFlow</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

<header class="header">

    <div>

        <h1>FixFlow</h1>

        <p>Edit Maintenance Issue</p>

    </div>

    <a href="index.php" class="btn secondary">
        Back
    </a>

</header>


<main class="container">

<section class="form-card">

    <h2>Edit Issue #<?php echo $issue["id"]; ?></h2>


    <?php if ($error !== ""): ?>

        <div class="alert error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <div class="form-group">

            <label>Issue Title</label>

            <input
                type="text"
                name="title"
                value="<?php echo htmlspecialchars($issue["title"]); ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>Description</label>

            <textarea
                name="description"
                rows="5"
                required
            ><?php echo htmlspecialchars($issue["description"]); ?></textarea>

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>Category</label>

                <select name="category" required>

                    <?php

                    $categories = [
                        "Electrical",
                        "Plumbing",
                        "Furniture",
                        "Computer",
                        "Other"
                    ];

                    foreach ($categories as $category):

                    ?>

                        <option
                            value="<?php echo $category; ?>"
                            <?php
                            echo $issue["category"] === $category
                                ? "selected"
                                : "";
                            ?>
                        >
                            <?php echo $category; ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="form-group">

                <label>Priority</label>

                <select name="priority">

                    <?php

                    foreach (
                        ["Low", "Medium", "High"]
                        as $priority
                    ):

                    ?>

                        <option
                            value="<?php echo $priority; ?>"
                            <?php
                            echo $issue["priority"] === $priority
                                ? "selected"
                                : "";
                            ?>
                        >
                            <?php echo $priority; ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>


        <div class="form-group">

            <label>Status</label>

            <select name="status">

                <?php

                foreach (
                    ["Reported", "In Progress", "Resolved"]
                    as $status
                ):

                ?>

                    <option
                        value="<?php echo $status; ?>"
                        <?php
                        echo $issue["status"] === $status
                            ? "selected"
                            : "";
                        ?>
                    >
                        <?php echo $status; ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <div class="form-group">

            <label>Location</label>

            <input
                type="text"
                name="location"
                value="<?php echo htmlspecialchars($issue["location"]); ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>Reported By</label>

            <input
                type="text"
                name="reported_by"
                value="<?php echo htmlspecialchars($issue["reported_by"]); ?>"
                required
            >

        </div>


        <button type="submit" class="btn">
            Save Changes
        </button>

    </form>

</section>

</main>

</body>
</html>