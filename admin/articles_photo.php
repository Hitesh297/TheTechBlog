<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

// redirect to root if sessionid doesnt exists
secure();

// redirect to articles page if id is not received
if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: articles.php' );
  die();
  
}

// save photo when request with a file is received
if( isset( $_FILES['photo'] ) )
{
  
    if( $_FILES['photo']['error'] == 0 )
    {

      switch( $_FILES['photo']['type'] )
      {
        case 'image/png': 
          $type = 'png'; 
          break;
        case 'image/jpg':
        case 'image/jpeg':
          $type = 'jpeg'; 
          break;
        case 'image/gif': 
          $type = 'gif'; 
          break;      
      }

      $query = 'UPDATE articles SET
        photo = "data:image/'.$type.';base64,'.base64_encode( file_get_contents( $_FILES['photo']['tmp_name'] ) ).'"
        WHERE id = '.$_GET['id'].'
        LIMIT 1';
      mysqli_query( $connect, $query );

    }
    
  set_message( 'Article photo has been updated' );

  // redirect to articles page after save is complete
  header( 'Location: articles.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  // if the request contains delete, initiate delete
  if( isset( $_GET['delete'] ) )
  { 
    $query = 'UPDATE articles SET
      photo = ""
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    $result = mysqli_query( $connect, $query );
    
    set_message( 'Article photo has been deleted' );
    
    // redirect to articles page after delete
    header( 'Location: articles.php' );
    die();
    
  }
  
  $query = 'SELECT *
    FROM articles
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: articles.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

include( 'includes/header.php' );

include 'includes/wideimage/WideImage.php';

?>

<h2>Edit Article Photo</h2>

<?php if( $record['photo'] ): ?>

  <?php

  $data = base64_decode( explode( ',', $record['photo'] )[1] );
  $img = WideImage::loadFromString( $data );
  $data = $img->resize( 200, 200, 'outside' )->crop( 'center', 'center', 200, 200 )->asString( 'jpg', 70 );

  ?>
  <p><img src="image.php?type=article&id=<?php echo $record['id']; ?>&width=300&height=300&format=inside"></p>
  <p><a class="link" href="articles_photo.php?id=<?php echo $_GET['id']; ?>&delete">Delete this Photo</a></p>

<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  
  <label for="photo">Photo:</label>
  <input type="file" name="photo" id="photo">
  
  <br>
  
  <input class="button-input" type="submit" value="Save Photo">
  
</form>

<p><a class="link" href="articles.php">❮ Return to Article List</a></p>


<?php

include( 'includes/footer.php' );

?>