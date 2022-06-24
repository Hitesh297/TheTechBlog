<?php
include('admin/includes/database.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>The Tech Blog</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="description" content="blog about technology">
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <header id="header">
        <!--Site Name-->
        <div class="header-box">
            <h2 id="site-name"><a href="index.php">TheTechBlog</a></h2>
        </div>
        <!--Navigation Links-->
        <nav id="main-menu" aria-label="Main navigation">
            <div class="header-box">
                <ul class="menu">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Top 10</a></li>
                    <li><a href="#">Blogs</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <?php
    if (isset($_GET['id'])) {

        $query = 'SELECT art.id AS "articleid",art.*,a.* FROM articles as art LEFT JOIN author as a on a.id = art.authorId WHERE art.id =' . $_GET['id'];
        $result = mysqli_query($connect, $query);
        $record = mysqli_fetch_assoc($result);
    } else {
        $query = 'SELECT art.id AS "articleid",art.*,a.* FROM articles as art LEFT JOIN author as a on a.id = art.authorId WHERE art.id = 1';
        $result = mysqli_query($connect, $query);
        $record = mysqli_fetch_assoc($result);
    }
    ?>
    <div class="flex-container">
        <main id="main">
            <article>
                <!--Article title-->
                <h2><?php echo $record['title']; ?></h2>
                <div class="byline">
                    <span>By <span class="bold-text"><?php echo $record['firstName'] . " " . $record['lastName']; ?></span> published on <?php echo $record['publishDate']; ?></span>
                </div>
                <!--Article content-->
                <div id="article-img">
                    <img src="admin/image.php?type=article&id=<?php echo $record['articleid']; ?>&width=600&height=300" alt="picture related to the article">
                </div>
                <?php echo $record['content']; ?>

            </article>
            <!-- Author Details -->
            <section class="author-block">
                <div class="author-identity">
                    <div class="avatar">
                        <img src="admin/image.php?type=author&id=<?php echo $record['id']; ?>&width=50&height=50" alt="picture of author" width="50" height="50">
                    </div>
                    <div class="author">
                        <h2 class="name"><?php echo $record['firstName'] . " " . $record['lastName']; ?></h2>
                    </div>
                    <div class="author-links">
                        <a href="<?php echo $record['linkedinUrl']; ?>">
                            <img src="images/linkedin.png" alt="linkedin logo" width="20" height="20">
                        </a>
                    </div>
                </div>
                <div class="bio">
                    <p><?php echo $record['bio']; ?></p>
                </div>
            </section>
        </main>
        <?php
        // Get all Articles
        $getallarticles = 'SELECT *
                    FROM articles
                    ORDER BY publishDate DESC';
        $articleListResult = mysqli_query($connect, $getallarticles);

        ?>

        <!--Article list sidebar-->
        <aside id="sidebar-one">
            <h2 id="latest-posts">LATEST POSTS</h2>
            <ul>
                <?php while ($article = mysqli_fetch_assoc($articleListResult)) : ?>
                    <li>
                        <div class="sidebarlist post-item">
                            <img src="admin/image.php?type=article&id=<?php echo $article['id']; ?>&width=80&height=80" alt="image of youtube icon" height="50">
                            <a class="sidebar-links" href="index.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </aside>
    </div>

    <!--Footer-->
    <footer id="footer" aria-label="Footer">
        <div class="header-box">
            <div class="copyrights">
                © TheTechBlog, 2020.
            </div>
        </div>
    </footer>
</body>

</html>