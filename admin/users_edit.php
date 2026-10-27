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

        $query = $connect->prepare('UPDATE users SET
      first = :first,
      last = :last,
      email = :email,
      active = :active
      WHERE id = :id');
        $query->execute([
            'first' => $_POST['first'],
            'last' => $_POST['last'],
            'email' => $_POST['email'],
            'active' => $_POST['active'],
            'id' => (int) $_GET['id'],
        ]);

        if ($_POST['password']) {

            $query = $connect->prepare('UPDATE users SET password = :password WHERE id = :id');
            $query->execute([
                'password' => md5($_POST['password']),
                'id' => (int) $_GET['id'],
            ]);
        }

        set_message('User has been updated');
    }

    header('Location: users.php');
    die();
}

// display form with existing values when get request is received 
if (isset($_GET['id'])) {

    $query = $connect->prepare('SELECT TOP (1) * FROM users WHERE id = :id');
    $query->execute(['id' => (int) $_GET['id']]);
    $record = $query->fetch();

    if (!$record) {

        header('Location: users.php');
        die();
    }

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