<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

// redirect to root if sessionid doesnt exists
secure();

// redirect to users page if id is not received
if (!isset($_GET['id'])) {

    header('Location: users.php');
    die();
}

// initiate save when post request is received
if (isset($_POST['first'])) {

    if ($_POST['first'] and $_POST['last'] and $_POST['email']) {

        $query = 'UPDATE users SET
      first = "' . mysqli_real_escape_string($connect, $_POST['first']) . '",
      last = "' . mysqli_real_escape_string($connect, $_POST['last']) . '",
      email = "' . mysqli_real_escape_string($connect, $_POST['email']) . '",
      active = "' . $_POST['active'] . '"
      WHERE id = ' . $_GET['id'] . '
      LIMIT 1';
        mysqli_query($connect, $query);

        if ($_POST['password']) {

            $query = 'UPDATE users SET
        password = "' . md5($_POST['password']) . '"
        WHERE id = ' . $_GET['id'] . '
        LIMIT 1';
            mysqli_query($connect, $query);
        }

        set_message('User has been updated');
    }

    header('Location: users.php');
    die();
}

// display form with existing values when get request is received 
if (isset($_GET['id'])) {

    $query = 'SELECT *
    FROM users
    WHERE id = ' . $_GET['id'] . '
    LIMIT 1';
    $result = mysqli_query($connect, $query);

    if (!mysqli_num_rows($result)) {

        header('Location: users.php');
        die();
    }

    $record = mysqli_fetch_assoc($result);
}

include('includes/header.php');

?>

<h2>Edit User</h2>

<form method="post">
    <table>
        <tr>
            <td>
                <label for="first">First:</label>
            </td>
            <td>
                <input type="text" name="first" id="first" value="<?php echo htmlentities($record['first']); ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="last">Last:</label>
            </td>
            <td>
                <input type="text" name="last" id="last" value="<?php echo htmlentities($record['last']); ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="email">Email:</label>
            </td>
            <td>
                <input type="email" name="email" id="email" value="<?php echo htmlentities($record['email']); ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="password">Password:</label>
            </td>
            <td>
                <input type="password" name="password" id="password">
            </td>
        </tr>
        <tr>
            <td>
                <label for="active">Active:</label>
            </td>
            <td>
                <?php

                $values = array('Yes', 'No');

                echo '<select name="active" id="active">';
                foreach ($values as $key => $value) {
                    echo '<option value="' . $value . '"';
                    if ($value == $record['active']) echo ' selected="selected"';
                    echo '>' . $value . '</option>';
                }
                echo '</select>';

                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input class="button-input" type="submit" value="Edit User">
            </td>
        </tr>
    </table>
</form>

<p><a class="link" href="users.php">❮ Return to User List</a></p>


<?php

include('includes/footer.php');

?>