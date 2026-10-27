<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

// redirect to root if sessionid doesnt exists
secure();

// initiate save if post request is received
if (isset($_POST['firstName'])) {

  if ($_POST['lastName'] and $_POST['bio']) {

    $query = $connect->prepare('INSERT INTO author (
        firstName,
        lastName,
        bio,
        linkedinUrl
      ) VALUES (
         :firstName,
         :lastName,
         :bio,
         :linkedinUrl
      )');
    $query->execute([
      'firstName' => $_POST['firstName'],
      'lastName' => $_POST['lastName'],
      'bio' => $_POST['bio'],
      'linkedinUrl' => $_POST['linkedinUrl'],
    ]);

    set_message('Author has been added');
  }

  header('Location: authors.php');
  die();
}

include('includes/header.php');

?>

<h2>Add Author</h2>

<form method="post">
  <table>
    <tr>
      <td>
        <label for="firstName">First Name:</label>
      </td>
      <td>
        <input type="text" name="firstName" id="firstName">
      </td>
    </tr>
    <tr>
      <td>
        <label for="lastName">Last Name:</label>
      </td>
      <td>
        <input type="text" name="lastName" id="lastName">
      </td>
    </tr>
    <tr>
      <td>
        <label for="content">Bio:</label>
      </td>
      <td>
        <textarea type="text" name="bio" id="bio" rows="5"></textarea>
      </td>
    </tr>
    <script>
      ClassicEditor
        .create(document.querySelector('#bio'))
        .then(editor => {
          console.log(editor);
        })
        .catch(error => {
          console.error(error);
        });
    </script>

    <tr>
      <td>
        <label for="linkedinUrl">LinkedIn URL:</label>
      </td>
      <td>
        <input type="url" name="linkedinUrl" id="linkedinUrl">
      </td>
    </tr>
    <tr>
      <td align="center" colspan="2">
        <input class="button-input" type="submit" value="Add Author">
      </td>
    </tr>
  </table>
</form>

<p><a class="link" href="authors.php">❮ Return to Author List</a></p>


<?php

include('includes/footer.php');

?>