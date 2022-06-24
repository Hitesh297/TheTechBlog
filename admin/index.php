<?php
include( 'includes/config.php' );
include( 'includes/database.php' );
include( 'includes/functions.php' );
include( 'includes/header.php' );
?>

<?php

// redirect to dashboard if user already logged in
if(isset($_SESSION['id']))
{
    header( 'Location: dashboard.php' );
    die();
}

// initiate authentication when email is received in post request
if( isset( $_POST['email'] ) )
{
  
  $query = 'SELECT *
    FROM users
    WHERE email = "'.$_POST['email'].'"
    AND password = "'.md5( $_POST['password'] ).'"
    AND active = "Yes"
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( mysqli_num_rows( $result ) )
  {
    
    $record = mysqli_fetch_assoc( $result );
    
    $_SESSION['id'] = $record['id'];
    $_SESSION['email'] = $record['email'];
    
    header( 'Location: dashboard.php' );
    die();
    
  }
  else
  {
    
    set_message( 'Incorrect email and/or password' );
    
    header( 'Location: index.php' );
    die();
    
  } 
  
}

?>

<div style="max-width: 400px; margin:auto">

    <form method="post">

        <table id="auth-form-table">
            <tr>
                <td>
                    <label for="email">Email:</label>
                </td>
                <td>
                    <input type="text" name="email" id="email">
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
                    <input type="submit" value="Login">
                </td>
                <td></td>
            </tr>
        </table>

    </form>

</div>


<?php
include('includes/footer.php');
?>