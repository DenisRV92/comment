<div class="error"><?php echo $_COOKIE['error'] ?? null ?></div>
<form action="/" method="POST">
    <!-- Add a hidden field "_method" with the value "PUT" to emulate a PUT request -->
    <input type="hidden" id="id" name="id"
           value="<?php echo isset($comment[0]['id']) ? htmlspecialchars($comment[0]['id']) : ''; ?>">

    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo isset($comment[0]['title']) ? htmlspecialchars($comment[0]['title']) : ''; ?>">
    <!-- Display the current title in the input field -->

    <label for="summary">Summary:</label>
    <textarea id="summary" name="summary"><?php echo isset($comment[0]['summary']) ? htmlspecialchars($comment[0]['summary']) : ''; ?></textarea>
    <!-- Display the current summary in the textarea -->

    <label for="body">Body:</label>
    <textarea id="body" name="body"><?php echo isset($comment[0]['body']) ? htmlspecialchars($comment[0]['body']) : ''; ?></textarea>
    <!-- Display the current body in the textarea -->

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" value="<?php echo isset($comment[0]['author']) ? htmlspecialchars($comment[0]['author']) : ''; ?>">
    <!-- Display the current author in the input field -->

    <button type="submit"><?php echo isset($comment[0]['id']) ? 'Update Comment' : 'Create comment'; ?></button>
</form>
