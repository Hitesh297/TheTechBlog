<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

// redirect to root if sessionid doesnt exists
secure();

// initiate save when post request is received
if (isset($_POST['first'])) {

    if ($_POST['first'] and $_POST['last'] and $_POST['email'] and $_POST['password']) {

        $query = $connect->prepare('INSERT INTO users (
        first,
        last,
        email,
        password,
        active,
        dateAdded
      ) VALUES (
        :first,
        :last,
        :email,
        :password,
        :active,
        SYSDATETIME()
      )');
        $query->execute([
            'first' => $_POST['first'],
            'last' => $_POST['last'],
            'email' => $_POST['email'],
            'password' => md5($_POST['password']),
            'active' => $_POST['active'],
        ]);

        set_message('User has been added');
    }

    header('Location: users.php');
    die();
}

include('includes/header.php');

?>

<h2>Add User</h2>

<form method="post">
    <table>
        <tr>
            <td>
                <label for="first">First Name:</label>
            </td>
            <td>
                <input type="text" name="first" id="first">
            </td>
        </tr>
        <tr>
            <td>
                <label for="last">Last Name:</label>
            </td>
            <td>
                <input type="text" name="last" id="last">
            </td>
        </tr>
        <tr>
            <td>
                <label for="email">Email:</label>
            </td>
            <td>
                <input type="email" name="email" id="email">
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
                    echo '>' . $value . '</option>';
                }
                echo '</select>';

                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input class="button-input" type="submit" value="Add User">
            </td>
        </tr>
    </table>
</form>

<p><a class="link" href="users.php">❮ Return to User List</a></p>


<?php

include('includes/footer.php');

?>