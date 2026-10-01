<?php

require_once "config/database.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $priority = $_POST["priority"] ?? "Medium";
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

        $sql = "INSERT INTO issues
                (
                    title,
                    description,
                    category,
                    location,
                    priority,
                    reported_by
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
            $title,
            $description,
            $category,
            $location,
            $priority,
            $reported_by
        );

        if ($stmt->execute()) {

            header("Location: index.php");
            exit;

        } else {

            $error = "Unable to report the issue.";

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

    <title>Report Issue - FixFlow</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

<header class="header">

    <div>

        <h1>FixFlow</h1>

        <p>Report a Maintenance Issue</p>

    </div>

    <a href="index.php" class="btn secondary">
        Back to Dashboard
    </a>

</header>


<main class="container">

    <section class="form-card">

        <h2>Report New Issue</h2>

        <p class="form-description">
            Provide information about the repair or maintenance problem.
        </p>


        <?php if ($error !== ""): ?>

            <div class="alert error">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label>Issue Title *</label>

                <input
                    type="text"
                    name="title"
                    placeholder="Example: Classroom light not working"
                    required
                >

            </div>


            <div class="form-group">

                <label>Description *</label>

                <textarea
                    name="description"
                    rows="5"
                    placeholder="Explain the problem..."
                    required
                ></textarea>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>Category *</label>

                    <select name="category" required>

                        <option value="">
                            Select Category
                        </option>

                        <option value="Electrical">
                            Electrical
                        </option>

                        <option value="Plumbing">
                            Plumbing
                        </option>

                        <option value="Furniture">
                            Furniture
                        </option>

                        <option value="Computer">
                            Computer
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Priority *</label>

                    <select name="priority" required>

                        <option value="Low">
                            Low
                        </option>

                        <option value="Medium" selected>
                            Medium
                        </option>

                        <option value="High">
                            High
                        </option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label>Location *</label>

                <input
                    type="text"
                    name="location"
                    placeholder="Example: Computer Lab 02"
                    required
                >

            </div>


            <div class="form-group">

                <label>Reported By *</label>

                <input
                    type="text"
                    name="reported_by"
                    placeholder="Your name"
                    required
                >

            </div>


            <button type="submit" class="btn">
                Report Issue
            </button>

        </form>

    </section>

</main>

</body>
</html>