<?php 

include_once "common/header.php"

?>

<div class="w-full grid items-center justifiy-center">

    <div class="card-big py-2 px-2 rounded-2" >
        <h1>Log in</h1>
        
        <div>

            <div>
                <form id="login_form" class="flex flex-column gap-1">

            
                    <label for="email">Email</label>
                    <input type="email" class="px-2 py-2 rounded-1 border-solid border-w-1 font-normal" id="email" name="email">

                    <label for="password">Password</label>
                    <input type="password" id="password" class="px-2 py-2 rounded-1 border-solid border-w-1 font-normal" name="password">

                    <div class="loader_wrap">
                        <div class="msg_text"></div>   
                        <div id='smallLoader' class="loader_div" ><div class='loaderAnim'></div><div class='loaderAnim2'></div><div class='loaderAnim3'></div></div>
                    </div>
                    
                    <button class="button-primary" onclick="sendLogin(event)" >login</button>
                </form>
                <p>Don't have an account yet? <a href="/register">Click here to register.</a></p>
            </div>
        
        </div>

    </div>

</div>



<?php 

include_once "common/footer.php"
?>