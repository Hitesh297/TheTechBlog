<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

// redirect to root if sessionid doesnt exists
secure();

// initiate delete if request contains delete
if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM articles
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
    
  set_message( 'Project has been deleted' );
  
  header( 'Location: articles.php' );
  die();
  
}

include( 'includes/header.php' );

$query = 'SELECT *
  FROM articles
  ORDER BY publishDate DESC';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Articles</h2>
<p><a class="link" href="articles_add.php">+ Add Article</a></p>
<table class="content-table">
  <tr>
    <th></th>
    <th>ID</th>
    <th>Title</th>
    <th>Date</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td>
         <img src="image.php?type=article&id=<?php echo $record['id']; ?>&width=300&height=300&format=inside">
      </td>
      <td><?php echo $record['id']; ?></td>
      <td>
        <span class="bold-text">
        <?php echo htmlentities( $record['title'] ); ?>
        </span>
        <small><?php echo substr($record['content'], 0, 300); ?>...</small>
      </td>
      <td style="white-space: nowrap;"><?php echo htmlentities( $record['publishDate'] ); ?></td>
      <td><a class="link" href="articles_photo.php?id=<?php echo $record['id']; ?>">Photo</i></a></td>
      <td><a class="link" href="articles_edit.php?id=<?php echo $record['id']; ?>">Edit</i></a></td>
      <td>
        <a class="link" href="articles.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this article?');">Delete</i></a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a class="link" href="articles_add.php">+ Add Article</a></p>

<?php

include( 'includes/footer.php' );

?>