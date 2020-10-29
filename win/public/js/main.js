document.addEventListener('DOMContentLoaded', () => {
  // Get all "navbar-burger" elements
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
  // Check if there are any navbar burgers
  if ($navbarBurgers.length > 0) {
    // Add a click event on each of them
    $navbarBurgers.forEach( el => {
      el.addEventListener('click', () => {
        // Get the target from the "data-target" attribute
        const target = el.dataset.target;
        const $target = document.getElementById(target);
        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        el.classList.toggle('is-active');
        $target.classList.toggle('is-active');
      });
    });
  }
});

///webcam
var video = document.getElementById('video'),
    photo = document.getElementById('photo'),
    select_photo = document.getElementById("photo-filter"),
    take = document.getElementById('take'),
    img64 = document.getElementById('img64');
    filter_64 = document.getElementById('filter'),
    input = document.getElementById('inpFile'),
    display_vedio = document.getElementById('display_vedio');;
    title_filter = document.getElementById('title_filter');;

if (video){
var canvas = document.getElementById('canvas'),
ctx = canvas.getContext('2d');

function getVideo() {
  navigator.mediaDevices.getUserMedia({ video: true })
  .then(localMediaStream => {      
    video.srcObject = localMediaStream;
    video.play();
  }).catch(err => console.error(err));
}

///filter

if (select_photo.options[0].value == ""){
  input.disabled = true;
  take.disabled = true;
}

function ch_filter(){
  video.style.filter = select_photo.value;
  take.disabled = false;
  input.disabled = false;
  title_filter.innerHTML = "";
  getVideo()
}

function snap(){
    canvas.width = video.clientWidth;
    canvas.height = video.clientHeight;
    // ctx.filter =  select_photo.value;
    ctx.drawImage(video, 0, 0, canvas.offsetWidth, canvas.offsetHeight);
    canvas.style.visibility = "hidden";
    canvas.style.position = "absolute";
}

function takephoto(){
  snap();
  img64.value = canvas.toDataURL().substring(22);
  filter_64.value = select_photo.value;
}

// getVideo();
}


function display(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(event) {
        var img = document.createElement('img');
        img.setAttribute('id', "imgP");
        img.setAttribute('src',event.target.result);
        img.style.filter = select_photo.value;
        photo.innerHTML = '';
        photo.insertBefore(img, photo.firstChild);
        input.filter = select_photo.value;
    }
    reader.readAsDataURL(input.files[0]);
   }
}

//file
function ch(){
  const fileInput = document.querySelector('#file-js input[type=file]');
  if (fileInput.files.length > 0) {
    const fileName = document.querySelector('#file-js .file-name');
    fileName.textContent = fileInput.files[0].name;
  }
  display_vedio.style.display = "none";
  display(input);
}

/// display like && comment

var like = document.getElementById('like');
if (like)
  like.style.display = "none";
var btn_display_like = document.getElementById('btn_display_like');
var btn_display_comment = document.getElementById('btn_display_comment');
var comment = document.getElementById('comment');
if (comment)
  comment.style.display = "none";
var form_comment = document.getElementsByName('form_comment');
for (i = 0; i < form_comment.length; i++){
  form_comment[i].style.display = "none";
}
  

function display_like(){
  if (like.style.display == "none"){
    like.style.display = "block";
    comment.style.display = "none";
  }
  else
    like.style.display = "none";
}

function display_comment(){
  if (comment.style.display == "none"){
    comment.style.display = "block";
    like.style.display = "none";
  }
  else
    comment.style.display = "none";
}

function display_comment_post(j){
    if (form_comment[j].style.display == "none")
      form_comment[j].style.display = "block";
    else
      form_comment[j].style.display = "none";
}

function like_ajax(id,j){
  var request = new XMLHttpRequest();
    // Instantiating the request object
    request.open("GET", "http://localhost/Camagru/posts/add_like/" + id);
    // Defining event listener for readystatechange event
    request.onreadystatechange = function() {
        // Check if the request is compete and was successful
        if(this.readyState === 4 && this.status === 200){
          var sub;
          var like = document.getElementById(j);
          if (like.innerHTML.length > 5)
            sub = like.innerHTML.substr(6);
          else
            sub = like.innerHTML;
          var value = Number(sub) + Number(this.responseText);
          like.innerHTML = "&nbsp;".concat(value);
        }
    };
    // Sending the request to the server
    request.send();
}

//delete comment ajax

function dlt_f_ajax(id, j){
  var p0 = document.getElementById('all_comment_p');
  var p = p0.innerHTML;
  p = p.substring(6);
  var elem_dlt = document.getElementById("elem_to_dlt"+j);
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/Camagru/posts/delete_comment/"+id, true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onload = function(){
    if (this.responseText != 1){
      //delete elemet
      elem_dlt.parentNode.removeChild(elem_dlt);
      //decrement nbr
      var r = +p - +1;
      p0.innerHTML = "&nbsp" + r;
    }
  }
  xhr.send();
}