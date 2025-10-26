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