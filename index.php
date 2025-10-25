<?php
include 'db.php';

try {
    $query = $pdo->query("SELECT id, title, created_at FROM posts ORDER BY created_at DESC");
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #758291ff;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .add-post {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-post:hover {
            background-color: #0056b3;
        }
        .posts-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .post-card {
            flex: 1 1 calc(33.33% - 20px);
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .post-card a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            display: block;
        }
        /* .post-card a:hover {
            text-decoration: underline;
        } */
        .post-date {
            color: #666;
            font-size: 0.9em;
            display: block;
            margin-top: 5px;
        }
        .no-posts {
            text-align: center;
            color: #666;
            font-style: italic;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .post-card {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <h1>Blog Posts</h1>
    <a href="new_posts.php" class="add-post">create ❤️</a>
    <div class="posts-grid">
        <?php if (empty($posts)): ?>
            <div class="no-posts">No posts found.</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <a href="post.php?id=<?php echo htmlspecialchars($post['id']); ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                    <span class="post-date">(<?php echo date('F j, Y, g:i A', strtotime($post['created_at'])); ?>)</span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>