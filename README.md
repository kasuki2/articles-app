# delocal
## Getting started

The app was developed on Ubuntu 22.04, Apache2 and php 8.2
Curl is necessary.

The api parts are in public/api folder with its own .htaccess

MySql dump is in the file: mysql-dump.sql - two tables, users and articles.

In api/Config/Config.php you can set the db credentials.

It is necessary to set the api url endpoint in public/assets/js/main.js,
it is line 1: const API_URL = "<example.com>/api/";

The vhost should serve the "public" folder.

In public/Config/Config.php - it is necessary to set the site url 

-------------------------------
----- APP USAGE ---------------

After registration, log in and go to ADD ARTICLE page.
Paste a url in the textarea and press GET ARTICLE DATA button.
The app tries to get the article's title, image and description based on meta tags, og:title, og:image etc.

If it is successful, the app shows the title, description and image of the article if it was able to get the data.
Click SAVE to save the article. It is possible to save the article even if there's no description or image. The pasted url and a title are required only.

Go to the ARTICLES page and you see a list of the saved articles. In the top right-hand corner of the page you can find a live search input.

If you click the "open book" sign, a new tab will open in the browser and you can read the article.
You can delete the article by clicking the trash can in the bottom right-hand corner of the cards.

An online version: https://homio.cloud/


