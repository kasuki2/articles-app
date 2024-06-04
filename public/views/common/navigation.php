<div class="navigation-wrap page-wide" >
    <a href="<?= (isset($_SESSION) && isset($_SESSION['loggedin'])) ? "/articles" : "/" ?>" style="margin-left:0">
        <div class="logo flex items-center gap-1" >
            <img src="../../assets/images/articles-logo-small.png" />
            <div class="text-accent font-small font-bold" >ARTICLES</div>
        </div>
    </a>
    

    <div class="navigation_wrap flex items-center">
        <nav>
         <?php if (isset($_SESSION) && isset($_SESSION["loggedin"]) ) : ?>
            <a href="/articles">articles</a>
            <a href="/add-article">add article</a>
            <a href="#" onclick="logout(event)" >logout</a>
            
         <?php else : ?>   
        <a href="login">login</a>
        <a href="register">register</a>
        <?php endif; ?>   
        </nav>
    </div>
    <div class="mobile-menu-button items-center text-accent" onclick="openMobileMenu()">
        MENU
    </div>
    <?php if (isset($_SESSION["name"])) : ?>
    <div class="user_name flex items-center text-accent" >
        Welcome, <?=  htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php endif; ?>
</div>
