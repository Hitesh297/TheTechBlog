
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>The Tech Blog - Admin</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script>
    <meta name="description" content="blog about technology">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <header id="header">
        <!--Site Name-->
        <div class="header-box">
            <h2 id="site-name"><a href="index.php">TheTechBlog - Admin</a></h2>
        </div>
        <!--Navigation Links-->
        <?php if(isset($_SESSION['id'])): ?>
        <nav id="main-menu" aria-label="Main navigation">
            <div class="header-box">
                <ul class="menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
        <?php endif; ?>
    </header>
    <?php echo get_message(); ?>
    <div class="page-container">