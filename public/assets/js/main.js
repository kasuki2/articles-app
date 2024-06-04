const API_URL = "http://dev.delocal.com.test/api/";
const ITEM_PER_PAGE = 10;

var currentRequest = null;

// handles sending AJAX request
function sendRequest(method, url, data) {
  return new Promise(function (resolve, reject) {
    if (currentRequest) {
      currentRequest.abort();
    }

    var xhr = new XMLHttpRequest();
    currentRequest = xhr;
    xhr.open(method, url, true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        currentRequest = null;
        if (xhr.status >= 200 && xhr.status < 300) {
          resolve(xhr.responseText);
        } else {
          console.log(xhr.responseText.message);
          reject(xhr);
        }
      }
    };
    if (data) {
      let dataString = JSON.stringify(data);
      xhr.send(dataString);
    } else {
      xhr.send();
    }
  });
}
// collects the values from a form
function getFormValues(formId) {
 
  const form = document.getElementById(formId);
  const formData = {};

  for (let i = 0; i < form.elements.length; i++) {
    const element = form.elements[i];

    if (element.name && element.type !== "submit") {
      formData[element.name] = element.value;
    }
  }

  return formData;
}

async function sendLogin(e) {
  e.preventDefault();

  let formData = getFormValues("login_form");
  console.log(formData);
  e.target.disabled = true;

  let loaderWrap = document.querySelector("#login_form .loader_div");
  loaderWrap.classList.add("visible");
  let msgText = document.querySelector(".msg_text");
  msgText.innerText = "Logging in...";
  msgText.classList.add("visible");

  try {

    await sendRequest("POST", API_URL + "auth", formData);
    msgText.innerText = "You are in.";
    location.href = "/articles";

  } catch (err) {
    if (err.response) {
      let respObj = JSON.parse(err.response);
      msgText.innerHTML = message("Error: " + respObj.message + ".", false);
    }
  } finally {
    e.target.disabled = false;
    loaderWrap.classList.add("hidden");
  }
}

async function sendRegister(e) {
  e.preventDefault();
  let formData = getFormValues("register_form");

  e.target.disabled = true;

  let loaderWrap = document.querySelector("#register_form .loader_div");
  loaderWrap.classList.add("visible");
  let msgText = document.querySelector(".msg_text");
  msgText.innerText = "Registering...";
  msgText.classList.add("visible");

  try {
    
    await sendRequest("POST", API_URL + "users", formData);
    msgText.innerHTML =  message("Successful registration. You can log in.");
  } catch (err) {
    if (err.response) {
      let respObj = JSON.parse(err.response);
      msgText.innerHTML =  message("Error: " + respObj.message + ".", false);
    }
  } finally {
    e.target.disabled = false;
    loaderWrap.classList.add("hidden");
  }
}

async function getArticleData(e) {
  e.preventDefault();

  
  let formData = getFormValues("article_url_form");
  console.log(formData);
  e.target.disabled = true;

  let loaderWrap = document.querySelector("#article_url_form .loader_div");
  loaderWrap.classList.add("visible");
  let msgText = document.querySelector("#article_url_form .msg_text");
  msgText.innerText = "Patience. Processing url...";
  msgText.classList.add("visible");

  let articleImage = document.querySelector("#article_og_image");
  let articleDescription = document.querySelector("#article_og_description");
  let articleTitle = document.querySelector("#article_og_title");

  try {
    //let result = await sendRequest("POST", "api/get-article-data", formData);
    let result = await sendRequest("POST", API_URL + "articleurls", formData);
    let resultObj = JSON.parse(result);

    articleImage.src = resultObj.imageurl || "";
    articleDescription.value = resultObj.description || "no description";
    articleTitle.value = resultObj.title || "no title";

    let successText = message("The url has been successfully processed.");
    msgText.innerHTML =
      successText += `<div>Check the results and click the save button below to save the article.</div>`;
  } catch (err) {
    if (err.status == 401) {
      location.href = "/";
    }

    if (err.response) {
      let respObj = JSON.parse(err.response);
      msgText.innerHTML = message("Error: " + respObj.message + ".", false);

      articleImage.src = "https://placehold.co/600x400?text=No+Image";
      articleDescription.value = "";
      articleTitle.value = "";

    }
  } finally {
    e.target.disabled = false;
    loaderWrap.classList.add("hidden");
  }
}

async function saveArticleData(e) {
  e.preventDefault();

  let formData = getFormValues("article_final_form");

  let articleUrl = document.querySelector("#article_url");
  let pastedArticleUrl = articleUrl.value;

  if (!pastedArticleUrl) {
    alert(
      "There is not article url. Without the url, you cannot save the article."
    );
    return;
  }

  formData.article_url = pastedArticleUrl;

  let imgElement = document.getElementById("article_og_image");
  let imgSrc = imgElement.getAttribute("src");
  formData.image_url = "";
  if (typeof imgSrc != "undefined") {
    formData.image_url = imgSrc;
  }

  e.target.disabled = true;

  let msgText = document.querySelector("#article_final_form .msg_text");
  let loaderDiv = document.querySelector("#article_final_form .loader_div");
  msgText.innerHTML = "Saving article...";
  msgText.classList.add("visible");
  loaderDiv.classList.add("visible");
  try {
    await sendRequest("POST", API_URL + "articles", formData);
    msgText.innerHTML = message("Success: article saved.");
  } catch (err) {
    if (err.response) {
      let respObj = JSON.parse(err.response);
      msgText.innerHTML = message("Error: " + respObj.message + ".", false);
    } else {
      msgText.innerHTML = message("Unknown error.", false);
    }
  } finally {
    e.target.disabled = false;
    loaderDiv.classList.remove("visible");
  }
}

function message(text, success = true) {
  let html = "";
  html += `<div class="${
    success ? "text-success" : "text-error"
  }" >${text}</div>`;
  return html;
}

async function logout(e) {
  e.preventDefault();
  try {
    await sendRequest("POST", API_URL + "logout", null);
    location.href = "/";
  } catch (err) {
    console.log(err);
    alert("Error: could not log out.");
  }
}
// sends a DELETE to API to delete an article
async function deleteArticle(e, id) {
  if (!confirm("Do your really wish to delete this article?")) {
    return;
  }

  e.target.disabled = true;
  let cardWrap = e.target.closest(".card");
  cardWrap.style.opacity = ".5";
  let formData = {};
  formData.id = id;

  try {
    let response = await sendRequest("DELETE", API_URL + `articles`, formData);
    console.log(response);
  } catch (err) {
    console.log(err);
    alert("Error deleting article.");
  } finally {
    e.target.disabled = false;
    getArticles();
  }
}
// creates some pagination buttons
function makePagination(pages) {
  let paginationWrap = document.querySelector(".pagination_wrap");
  console.log("making pagination");
  console.log(pages);
  let maxPages = Math.ceil(pages / ITEM_PER_PAGE);

  let html = "";

  if (maxPages > 1) {
    for (let i = 0; i < maxPages; i++) {
      html += `<button class="pagination_button" onclick="jumptToPage(${
        i + 1
      })" >${i + 1}</button>`;
    }
  }

  paginationWrap.innerHTML = html;
}
// gets the page value from URL
function getPageQueryFromUrl() {
  let currentUrl = window.location.href;
  let url = new URL(currentUrl);
  let params = new URLSearchParams(url.search);
  let page = params.get("page");
  return page;
}

function fillArticleGrid(articles) {
  let articleContainer = document.querySelector("#article_container");

  let html = "";
  articles.map((article) => {
    html += `<div class="card" >
  <div class="title">${article.title ? article.title : "No title"}</div>
  <div class="image" style="background-image: url(${
    article.image_url
      ? article.image_url
      : "https://placehold.co/600x400?text=No+Image"
  })" >
  </div>
  <div class="description">${
    article.description ? article.description : "No description"
  }</div>
  <div class="buttons"><a href="${
    article.url
  }" target="_blank" ><button class="read_it_button" title="Read article" ></button></a><button title="delete article" class="delete_button" onclick="deleteArticle(event, ${
      article.id
    })" ></button></div>
  </div>`;
  });
  articleContainer.innerHTML = html;
}

// gets the articles and passes data to fillArticleGrid
async function getArticles(e) {
  let articleContainer = document.querySelector("#article_container");

  let currentPage = getPageQueryFromUrl();

  let formData = {};
  if (currentPage) {
    formData.page = currentPage;
  } else {
    console.log("setting form data to nll");
    formData = null;
  }
  try {
    let query = "articles";
    if (currentPage) {
      query = `articles?page=${currentPage}`;
    }
    let response = await sendRequest("GET", API_URL + query, null);
    console.log(response);

    if (response) {
      let respObj = JSON.parse(response);
      //console.log(respObj);

      if (respObj.data) {
        console.log(respObj.data);
        console.log(respObj.count);
        makePagination(respObj.count);

        fillArticleGrid(respObj.data);
      } else {
        articleContainer.innerHTML = "<div>You have no articles.</div>";
      }
    } else {
      articleContainer.innerHTML = "<div>No articles have been found</div>";
    }
  } catch (err) {
    console.log(err);
    if (err.status == 401) {
      location.href = "/";
    }
    if (err.response) {
      let respObj = JSON.parse(err.response);
      articleContainer.innerHTML = message(
        "Error: " + respObj.message + ".",
        false
      );
    } else {
      articleContainer.innerHTML = message("Unknown error.", false);
    }
  }
}
// gets the search term and sends it to the API
async function handleSearch(e) {
  let searchTerm = e.target.value;

  if (searchTerm.length > 3) {
    try {
      query = `search?s=${searchTerm}`;

      let response = await sendRequest("POST", API_URL + query, null);

      if (response) {
        let respObj = JSON.parse(response);
        console.log(respObj);
        if (respObj.data) {
          fillArticleGrid(respObj.data);
        }
      }
    } catch (err) {
      console.log(err);
    }
  } else if (searchTerm.trim().length == 0) {
    getArticles();
  }
}

// updates the URL with pagination without reloading
function jumptToPage(pageNumber) {
  var currentUrl = window.location.href;

  // Remove any existing page parameter from the URL
  var updatedUrl = currentUrl.replace(/([?&])page=\d+/, "$1page=" + pageNumber);

  // If no page parameter was found, add it to the end of the URL
  if (currentUrl === updatedUrl && currentUrl.indexOf("?") === -1) {
    updatedUrl += "?page=" + pageNumber;
  } else if (currentUrl === updatedUrl) {
    updatedUrl += "&page=" + pageNumber;
  }
  // PushState to update the URL without reloading the page
  window.history.pushState({ path: updatedUrl }, "", updatedUrl);
  getArticles();
}
// open mobile menu
function openMobileMenu() {
  let overlay = document.querySelector(".overlay");
  let drawer = document.querySelector(".drawer");
  overlay.classList.remove("hide");

  drawer.classList.add("transition-slide");
  setTimeout(() => {
    drawer.classList.add("transform-none");
  }, 100);
}
// close mobile menu
function hideMobileMenu() {
  let overlay = document.querySelector(".overlay");
  let drawer = document.querySelector(".drawer");
  drawer.classList.remove("transform-none");
  setTimeout(() => {
    overlay.classList.add("hide");
  }, 300);
}

// get articles if url changes eg. pagination
function handlePopState(event) {
  if (location.pathname == "/articles") {
    getArticles();
  }
}

window.addEventListener("popstate", handlePopState);

window.onload = () => {
  handlePopState({ state: window.history.state });
};
