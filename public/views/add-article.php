<?php 

include_once "common/header.php"

?>

<div class="w-full grid items-center justifiy-center">

    <div class="card-max py-2 px-2 rounded-2" >
        <h1>Add a new article</h1>
        <h3>1. Copy and paste the url of an article below:</h3>
        <form id="article_url_form" >
            <div>
            <textarea class="url_text border-solid border-w-1 w-full rounded-1 px-1 py-1 resize-v" name="article_url" id="article_url"></textarea>
            </div>
            
            <div class="loader_wrap">
                <div class="msg_text"></div>   
                <div id='smallLoader' class="loader_div" ><div class='loaderAnim'></div><div class='loaderAnim2'></div><div class='loaderAnim3'></div></div>
            </div>

            <button class="button-primary" onclick="getArticleData(event)" >get article data</button>
            
        </form>
        <h3>2. Click SAVE below to save the article:</h3>
            <div class="mt-1">
                <form id="article_final_form" >
                    <div >
                        <label for="article_og_title" class="font-small" >Article title:</label>
                        <input type="text" class="border-box w-full px-1 py-1 rounded-1 border-solid border-w-1" name="title" id="article_og_title" value="" placeholder="article title" />
                    </div>
                    <div class="mt-1" >
                        <div class="font-small">Article image:</div>
                        <img src="" name="image" class="article_og_image mx-auto rounded-1 bg-light" id="article_og_image" alt="-- no article image --">
                    </div>
                    <div class="mt-1">
                        <div class="font-small" >Description:</div>
                        <div>
                            <textarea class="border-box w-full px-2 py-2 resize-v rounded-1 border-solid border-w-1" name="description" id="article_og_description" placeholder="article description" value="" ></textarea>
                        </div>
                    </div>

                    
                        <div class="loader_wrap">
                            <div class="msg_text"></div>   
                            <div id='smallLoader' class="loader_div" ><div class='loaderAnim'></div><div class='loaderAnim2'></div><div class='loaderAnim3'></div></div>
                        </div>
                    
                        <button onclick="saveArticleData(event)" id="save_article" class="button-primary">save</button>
                    </div>
                </form>
            </div>

            

    </div>

</div>



<?php 

include_once "common/footer.php"
?>