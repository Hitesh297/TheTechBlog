<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

// redirect to root if sessionid doesnt exists
secure();

// initiate delete if request contains delete
if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM author
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
    
  set_message( 'Author has been deleted' );
  
  header( 'Location: authors.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT *
  FROM author';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Authors</h2>
<p><a class="link" href="author_add.php">+ Add Author</a></p>
<table class="content-table">
  <tr>
    <th></th>
    <th>ID</th>
    <th>Name</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td>
         <img src="image.php?type=author&id=<?php echo $record['id']; ?>&width=200&height=300&format=inside">
      </td>
      <td><?php echo $record['id']; ?></td>
      <td>
      <span class="bold-text">
        <?php echo htmlentities( $record['firstName'] ).' '.htmlentities( $record['lastName'] ); ?>
      </span>
        <p><small><?php echo substr($record['bio'], 0, 300); ?>...</small></p>
      </td>
      <td><a class="link" href="author_photo.php?id=<?php echo $record['id']; ?>">Photo</i></a></td>
      <td><a class="link" href="author_edit.php?id=<?php echo $record['id']; ?>">Edit</i></a></td>
      <td>
        <a class="link" href="authors.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this author?');">Delete</i></a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a class="link" href="author_add.php">+ Add Author</a></p>

<?php

include( 'includes/footer.php' );

?>