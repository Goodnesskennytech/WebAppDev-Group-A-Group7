<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid post ID.");
}

// Handle post deletion
if (isset($_GET['delete']) && $_GET['delete'] == 1) {
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        die("Failed to delete post: " . $e->getMessage());
    }
}

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    if (!empty($name) && !empty($comment)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, name, comment) VALUES (?, ?, ?)");
            $stmt->execute([$id, $name, $comment]);
            header("Location: post.php?id=" . $id);  // Refresh to show new comment
            exit;
        } catch (PDOException $e) {
            $error = "Failed to add comment: " . $e->getMessage();
        }
    } else {
        $error = "Name and comment are required.";
    }
}

// Fetch the post
try {
    $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$post) {
        die("Post not found.");
    }

    // Fetch comments for this post
    $stmt = $pdo->prepare("SELECT name, comment, created_at FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
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
        }
        .header-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .back-link {
            color: #007bff;
            text-decoration: none;
        }
        /* .back-link:hover {
            text-decoration: underline;
        } */
        .delete-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border: 1px solid #dc3545;
            border-radius: 3px;
        }
        .delete-link:hover {
            background-color: #dc3545;
            color: white;
        }
        .post {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .post-date {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .post-content {
            line-height: 1.6;
            color: #333;
            margin-top: 20px;
        }
        .comments-section {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .comments-section h2 {
            color: #333;
            margin-bottom: 15px;
        }
        .comment {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        .comment:last-child {
            border-bottom: none;
        }
        .comment-name {
            font-weight: bold;
            color: #007bff;
        }
        .comment-text {
            color: #333;
            margin: 5px 0;
            line-height: 1.4;
        }
        .comment-date {
            color: #666;
            font-size: 0.8em;
        }
        .add-comment-form {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .add-comment-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .add-comment-form input[type="text"], .add-comment-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .add-comment-form textarea {
            height: 100px;
            resize: vertical;
        }
        .add-comment-form input[type="submit"] {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .add-comment-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 10px;
        }
        .no-comments {
            color: #666;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header-links">
        <a href="index.php" class="back-link">Back to Blog</a>
        <a href="post.php?id=<?php echo $id; ?>&delete=1" class="delete-link" onclick="return confirm('Are you sure you want to delete this post? This will also delete all comments.')">Delete Post</a>
    </div>
    <div class="post">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="post-date">Posted on <?php echo date('F j, Y, g:i A', strtotime($post['created_at'])); ?></p>
        <?php if (!empty($post['content'])): ?>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
        <?php else: ?>
            <p class="post-content">No content available for this post.</p>
        <?php endif; ?>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>
        <?php if (empty($comments)): ?>
            <p class="no-comments">No comments yet. Be the first to comment!</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-name"><?php echo htmlspecialchars($comment['name']); ?></div>
                    <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                    <div class="comment-date"><?php echo date('F j, Y, g:i A', strtotime($comment['created_at'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" class="add-comment-form">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" maxlength="100" required>
            
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" placeholder="Enter your comment..." required></textarea>
            
            <input type="submit" name="comment_submit" value="Add Comment">
        </form>
    </div>
</body>
</html>