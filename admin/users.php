<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

// redirect to root if sessionid doesnt exists
secure();

// initaite delete when request is received with delete
if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM users
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  set_message( 'User has been deleted' );
  
  header( 'Location: users.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT *
  FROM users 
  '.( ( $_SESSION['id'] != 1 and $_SESSION['id'] != 4 ) ? 'WHERE id = '.$_SESSION['id'].' ' : '' ).'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Users</h2>

<table class="content-table">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th></th>
    <th></th>
    <th>Is Active</th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td><?php echo $record['id']; ?></td>
      <td>
        <?php echo htmlentities( $record['first'] ); ?> <?php echo htmlentities( $record['last'] ); ?>
    </td>
      <td><a class="link" href="mailto:<?php echo htmlentities( $record['email'] ); ?>"><?php echo htmlentities( $record['email'] ); ?></a></td>
      <td><a class="link" href="users_edit.php?id=<?php echo $record['id']; ?>">Edit</a></td>
      <td>
          <a class="link" href="users.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this user?');">Delete</i></a>
      </td>
      <td>
        <?php echo $record['active']; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a class="link" href="users_add.php">+ Add User</a></p>


<?php

include( 'includes/footer.php' );

?>