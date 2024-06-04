<?php 

include_once "common/header.php"

?>

<div class="w-full pb-2">

    <div class="page-wide" >
        <div class="flex mt-2 space-between items-center">
        <h1>Articles</h1>
        <div><input type="search" class="py-1 px-1 rounded-1 border-solid border-w-1" id="search" placeholder="Search. Start typing..." onkeyup="handleSearch(event)" ></div>
        </div>
        

        <div id="article_container" class="article_container"></div>
        
        <div id="pagination_wrap" class="pagination_wrap mt-2 flex" >

        </div>

    </div>

</div>



<?php 

include_once "common/footer.php"
?>