<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

// redirect to root if sessionid doesnt exists
secure();

// redirect to articles list page if id not recieved
if (!isset($_GET['id']) and !isset($_POST['id'])) {

  header('Location: articles.php');
  die();
}

// initiate update if post request is received
if (isset($_POST['title'])) {

  if ($_POST['title'] and $_POST['content']) {

    $query = $connect->prepare('UPDATE articles SET
      title = :title,
      content = :content,
      publishDate = SYSDATETIME(),
      authorId = :authorId
      WHERE id = :id');
    $query->execute([
      'title' => $_POST['title'],
      'content' => $_POST['content'],
      'authorId' => (int) $_POST['authorId'],
      'id' => (int) $_POST['id'],
    ]);

    set_message( 'Article has been updated' );

  }

  header('Location: articles.php');
  die();
}

// display form with current values if get request is received
if (isset($_GET['id'])) {

  $query = $connect->prepare('SELECT TOP (1) * FROM articles WHERE id = :id');
  $query->execute(['id' => (int) $_GET['id']]);
  $result = $query;

  $record = $result->fetch();
  if (!$record) {

    header('Location: projects.php');
    die();
  }

}

include('includes/header.php');

?>

<h2>Edit Article</h2>

<form method="post">

  <input type="hidden" name="id" value="<?php echo htmlentities($record['id']); ?>">
<table>
  <tr>
    <td>
  <label for="title">Title:</label>
  </td>
  <td>
  <input type="text" name="title" id="title" value="<?php echo htmlentities($record['title']); ?>">
  </td>
  </tr>
  <tr>
  <td>
  <label for="content">Content:</label>
  </td>
  <td>
  <textarea type="text" name="content" id="content" rows="5"><?php echo htmlentities($record['content']); ?></textarea>
  </td>
  </tr>
  <script>
    ClassicEditor
      .create(document.querySelector('#content'))
      .then(editor => {
        console.log(editor);
      })
      .catch(error => {
        console.error(error);
      });
  </script>
  <tr>
  <td>
  <label for="author">Author:</label>
  </td>
  <td>
  <?php

  $authorsResult = $connect->query('SELECT * FROM author');

  echo '<select name="authorId" id="authorId">';
  while ($author = $authorsResult->fetch()) {
    echo '<option value="' . $author['id'] . '"';
    if ($author['id'] == $record['authorId']) echo ' selected="selected"';
    echo '>' . $author['firstName'] . ' ' . $author['lastName'] . '</option>';
  }
  echo '</select>';

  ?>
  </td>
  </tr>
  <tr>
  <td align="center" colspan="2">
  <input class="button-input" type="submit" value="Edit Article">
  </td>
  <tr>
  </table>
</form>

<p><a class="link" href="articles.php">❮ Return to Article List</a></p>


<?php

include('includes/footer.php');

?>