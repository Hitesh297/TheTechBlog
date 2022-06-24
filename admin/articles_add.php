<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

// redirect to root if sessionid doesnt exists
secure();

// start save if post request is received
if (isset($_POST['title'])) {

  if ($_POST['title'] and $_POST['content']) {

    $query = 'INSERT INTO articles (
        title,
        content,
        publishDate,
        authorId
      ) VALUES (
         "' . mysqli_real_escape_string($connect, $_POST['title']) . '",
         "' . mysqli_real_escape_string($connect, $_POST['content']) . '",
         NOW(),
         "' . mysqli_real_escape_string($connect, $_POST['authorId']) . '"
      )';
    mysqli_query($connect, $query);

    set_message('Article has been added');
  }

  header('Location: articles.php');
  die();
}

include('includes/header.php');

?>

<h2>Add Article</h2>

<form method="post">
  <table>
    <tr>
      <td>
        <label for="title">Title:</label>
      </td>
      <td>
        <input type="text" name="title" id="title">
      </td>
    </tr>
    <tr>
      <td>
        <label for="content">Content:</label>
      </td>
      <td>
        <textarea type="text" name="content" id="content" rows="5"></textarea>
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
        // get authors to populate dropdown
        $authorsquery = 'SELECT *
        FROM author';

        $authorsResult = mysqli_query($connect, $authorsquery);

        echo '<select name="authorId" id="authorId">';
        while ($author = mysqli_fetch_assoc($authorsResult)) {
          echo '<option value="' . $author['id'] . '"';
          echo '>' . $author['firstName'] . ' ' . $author['lastName'] . '</option>';
        }
        echo '</select>';

        ?>
      </td>
    </tr>
    <tr>
      <td align="center" colspan="2">
        <input class="button-input" type="submit" value="Add Article">
      </td>
    </tr>
  </table>
</form>

<p><a class="link" href="articles.php">❮ Return to Article List</a></p>


<?php

include('includes/footer.php');

?>