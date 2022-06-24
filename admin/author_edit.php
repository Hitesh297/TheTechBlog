<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

// redirect to root if sessionid doesnt exists
secure();

// redirect to authors page if id is not received
if (!isset($_GET['id']) and !isset($_POST['id'])) {

    header('Location: authors.php');
    die();
}

// initiate save if post request is received
if (isset($_POST['firstName'])) {

    if ($_POST['lastName'] and $_POST['bio']) {

        $query = 'UPDATE author SET
      firstName = "' . mysqli_real_escape_string($connect, $_POST['firstName']) . '",
      lastName = "' . mysqli_real_escape_string($connect, $_POST['lastName']) . '",
      bio = "' . mysqli_real_escape_string($connect, $_POST['bio']) . '",
      linkedinUrl = "' . mysqli_real_escape_string($connect, $_POST['linkedinUrl']) . '"
      WHERE id = ' . $_POST['id'] . '
      LIMIT 1';
        mysqli_query($connect, $query);

        set_message('Author has been updated');
    }

    header('Location: authors.php');
    die();
}

// display form with existing values if get request is received
if (isset($_GET['id'])) {

    $query = 'SELECT *
    FROM author
    WHERE id = ' . $_GET['id'] . '
    LIMIT 1';
    $result = mysqli_query($connect, $query);

    if (!mysqli_num_rows($result)) {

        header('Location: authors.php');
        die();
    }

    $record = mysqli_fetch_assoc($result);
}

include('includes/header.php');

?>

<h2>Edit Author</h2>

<form method="post">

    <input type="hidden" name="id" value="<?php echo htmlentities($record['id']); ?>">
    <table>
        <tr>
            <td>
                <label for="firstName">First Name:</label>
            </td>
            <td>
                <input type="text" name="firstName" id="firstName" value="<?php echo htmlentities($record['firstName']); ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="lastName">Last Name:</label>
            </td>
            <td>
                <input type="text" name="lastName" id="lastName" value="<?php echo htmlentities($record['lastName']); ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="bio">Content:</label>
            </td>
            <td>
                <textarea type="text" name="bio" id="bio" rows="5"><?php echo htmlentities($record['bio']); ?></textarea>
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
                <label for="linkedinUrl">Linkedin URL:</label>
            </td>
            <td>
        <input type="url" name="linkedinUrl" id="linkedinUrl" value="<?php echo htmlentities($record['linkedinUrl']); ?>">
      </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <input class="button-input" type="submit" value="Edit Author">
            </td>
        <tr>
    </table>
</form>

<p><a class="link" href="authors.php">❮ Return to Author List</a></p>


<?php

include('includes/footer.php');

?>