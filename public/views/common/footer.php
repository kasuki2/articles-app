<script src="../../assets/js/main.js"></script> 
</main>
<footer>
    <div class="page-wide">
    <div>ARTICLES</div>
    <div>Your article collecton</div>
    </div>
    
</footer>
<div class="overlay hide" onclick="hideMobileMenu(event)" >
<div class="drawer">
<div>
    <div class="flex" >
    <div class="my-2 mx-2" >
        <nav class="flex flex-column gap-2 px-2 py-2" >
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
    </div>
</div>
</div>
</div>
</body>
</html>
