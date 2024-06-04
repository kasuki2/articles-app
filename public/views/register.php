<?php 
include_once "common/header.php"
?>

<div class="w-full grid items-center justifiy-center">

    <div class="card-big py-2 px-2 rounded-2" >
        <h1>Register</h1>
        <div>

            <div>
                <form id="register_form" class="flex flex-column gap-1">

                
                    <label for="name">Name</label>
                    <input type="text" class="px-2 py-2 rounded-1 border-solid border-w-1 font-normal" id="name" name="name">

                    <label for="email">Email</label>
                    <input type="email" class="px-2 py-2 rounded-1 border-solid border-w-1 font-normal" id="email" name="email">

                    <label for="password">Password</label>
                    <input type="password" class="px-2 py-2 rounded-1 border-solid border-w-1 font-normal" id="password" name="password">

                    <label for="confirm_password">Confirm password</label>
                    <input type="password" class="px-2 py-2 rounded-1 border-solid border-w-1 font-normal" id="confirm_password" name="confirm_password">
                    <div class="loader_wrap">
                        <div class="msg_text"></div>   
                        <div id='smallLoader' class="loader_div" ><div class='loaderAnim'></div><div class='loaderAnim2'></div><div class='loaderAnim3'></div></div>
                    </div>
                    
                    <button class="button-primary" onclick="sendRegister(event)" >register</button>
                </form>
                <p>Already have an account? <a href="/login">Click here to log in.</a></p>
            </div>
        
        </div>

    </div>

</div>

<?php 

include_once "common/footer.php"
?>