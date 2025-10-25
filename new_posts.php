<?php
include 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if (!empty($title)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
            $stmt->execute([$title, $content]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Failed to add post: " . $e->getMessage();
        }
    } else {
        $error = "Title is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #758291ff;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
        /* .back-link:hover {
            text-decoration: underline;
        } */
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            height: 200px;
            resize: vertical;
            font-family: inherit;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Add New Post</h1>
    <a href="index.php" class="back-link">Back to Blog</a>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter post title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
        
        <label for="content">Content:</label>
        <textarea id="content" name="content" placeholder="Enter post content..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        
        <input type="submit" value="Add Post">
    </form>
</body>
</html>