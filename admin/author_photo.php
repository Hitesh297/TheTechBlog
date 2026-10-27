<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

// redirect to root if sessionid doesnt exists
secure();

// redirect to authors page if id is not received
if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: authors.php' );
  die();
  
}

// initiate save is request contains file
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

      $query = $connect->prepare('UPDATE author SET photo = :photo WHERE id = :id');
      $query->execute([
        'photo' => 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($_FILES['photo']['tmp_name'])),
        'id' => (int) $_GET['id'],
      ]);

    }
    
  set_message( 'Author photo has been updated' );

  header( 'Location: authors.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  // initiate delete if request contains delete
  if( isset( $_GET['delete'] ) )
  {
    
    $query = $connect->prepare("UPDATE author SET photo = '' WHERE id = :id");
    $query->execute(['id' => (int) $_GET['id']]);
    
    set_message( 'Author photo has been deleted' );
    
    header( 'Location: authors.php' );
    die();
    
  }
  
  // display existing values on get request
  $query = $connect->prepare('SELECT TOP (1) * FROM author WHERE id = :id');
  $query->execute(['id' => (int) $_GET['id']]);
  $record = $query->fetch();

  if (!$record)
  {
    
    header( 'Location: authors.php' );
    die();
    
  }
  
}

include( 'includes/header.php' );

include 'includes/wideimage/WideImage.php';

?>

<h2>Edit Author Photo</h2>

<?php if( $record['photo'] ): ?>

  <?php
  // we have encoded image to base64 before saving to database
  // decode from base64 to display the image
  $data = base64_decode( explode( ',', $record['photo'] )[1] );
  $img = WideImage::loadFromString( $data );
  $data = $img->resize( 200, 200, 'outside' )->crop( 'center', 'center', 200, 200 )->asString( 'jpg', 70 );

  ?>
  <p><img src="image.php?type=author&id=<?php echo $record['id']; ?>&width=300&height=300&format=inside"></p>
  <p><a class="link" href="author_photo.php?id=<?php echo $_GET['id']; ?>&delete">Delete this Photo</a></p>

<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  
  <label for="photo">Photo:</label>
  <input type="file" name="photo" id="photo">
  
  <br>
  
  <input class="button-input" type="submit" value="Save Photo">
  
</form>

<p><a class="link" href="authors.php">❮ Return to Author List</a></p>


<?php

include( 'includes/footer.php' );

?>