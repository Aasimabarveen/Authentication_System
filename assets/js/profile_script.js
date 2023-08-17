// import { isTokenExpired } from 'token_expiry.js';
var jQueryScript = document.createElement('script');  
jQueryScript.setAttribute('src','https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
document.head.appendChild(jQueryScript);

// Retrieve the JWT from session storage
let jwt = sessionStorage.getItem('jwt');

fname=document.querySelector("#Fname");
lname=document.querySelector("#Lname");
dob=document.querySelector("#Dob");
contact=document.querySelector("#Contact");
efname=document.querySelector("#fname");
elname=document.querySelector("#lname");
edob=document.querySelector("#dob");
econtact=document.querySelector("#contact");
error=document.querySelector(".error");
perror=document.querySelector(".perror");
progress_bar=document.querySelector(".progress-bar");
let payload="";
document.addEventListener('DOMContentLoaded', () => {
  history.pushState(null, null, location.href);
  window.onpopstate = function (event) {
    history.go(1);
  }
  if(!jwt||jwt==='undefined'){
    sessionStorage.removeItem('jwt');
    window.location.href = './login.html'; // Redirect to login page
    
  }
  if (jwt) {
    console.log("jwt "+jwt);
    const parsejwt = parseJwt(jwt);
    console.log("dfgdfg "+parsejwt);
    if(isTokenExpired(parsejwt.exp)){
        sessionStorage.removeItem('jwt');
        window.location.href = './login.html';
        }
       
  }
    payload = parseJwt(jwt);
    load_userProfile(payload.user);
  
});

// Add an event listener to the logout link/button
document.querySelector('.Logout').addEventListener('click', function(event) {
  event.preventDefault(); // Prevent the default link behavior
  logout(); // Call your logout function
});

document.querySelector('.editprofile').addEventListener("click",()=>{
    event.preventDefault();
    toggle_view();
});

document.querySelector('.back').addEventListener("click",()=>{
   toggle_view();
});

document.querySelector('#update').addEventListener("click",()=>{

    error.innerHTML = "";
    event.preventDefault();
   if(validate_update())
    {   
        console.log("validate update details perform api works");
        update_userDetails();
    }
});

validate_update=()=>{
    var ToDate = new Date();
    var p=/^[0-9]]{10}$/;
    if(efname.value==""){
        set_error(error,"Please Enter First Name","red");    
        return;    
    }
    if (!(new Date(edob.value) < ToDate)) {
        set_error(error,"Please Enter Valid DoB "+ToDate+" "+new Date(edob.value) ,"red");    
        return; 
     }
    if(parseInt(econtact.value.length)!=10){
        set_error(error,"Please Enter Valid Phone Number "+contact.value.length+","+"red");       
        return;
    }
    if(p.test(econtact.value)) {
        set_error(error,"Please Enter Valid Phone Number","red");
        return;     
     }
     if(fname.innerHTML.trim() == efname.value.trim()&&lname.innerHTML.trim()==elname.value.trim()&&dob.innerHTML.trim()==edob.value.trim()&&contact.innerHTML.trim()==econtact.value.trim()){
        set_error(error,"No updates found in data","Green");
        return;
     }
     else
        console.log(fname.innerHTML.trim() == efname.value.trim());
    return true;    
}

set_error=(name,msg,color)=>{
    name.innerHTML = msg;
    name.style.display = "block";
    name.style.color=color;
}

clear_data=()=>{
    efname.value="";
    elname.value="";
    edob.value="";
    econtact.value="";
}

update_userDetails=()=>{
    const url = 'http://192.168.56.1/authenticate/api/update.php';
    const data = {
        fname: efname.value,
        lname: elname.value,
        dob:edob.value,
        contact:econtact.value,
        id:payload.user['id'],
        token:jwt
    };

    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
         beforeSend: function() {
            toggle_update();
            document.querySelector('.Logout').style.display="none";
        },
        success: function(response) {
            if (response.message &&response.message.includes("Succesfully")) {
                set_error(perror,response.message,"green");
                token=response.received_data;
                document.querySelector(".perror").classList.toggle("show");
                sessionStorage.removeItem('jwt');
                jwt="";
                sessionStorage.setItem('jwt',token['token']);
                jwt=sessionStorage.getItem('jwt');
                payload = parseJwt(jwt);
                load_userProfile(payload.user);
                progressbarRender();
                clear_data();
            }
            else {
                set_error(error,data.message,"red");
                console.log(data.message);
                document.querySelector(".progress").classList.toggle("progress_hide");
                 return false;
            } 

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', "", errorThrown);
        },
        complete: function() {
      }
});
}


function toggle_view(){
    document.querySelector('.EditForm').classList.toggle("show");
    document.querySelector('.userDetails').classList.toggle("hide");
    document.querySelector('.back').classList.toggle("show");
    document.querySelector('.editprofile').classList.toggle("hide");
}

hidenotify=()=>{
    setTimeout(()=>{
        document.querySelector('.perror').innerHTML="";},2000);

}
// Function to parse JWT payload
function parseJwt(token) {
    try {
        const base64Url = token.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const decoded = atob(base64);
        return JSON.parse(decoded);
        console.log("token "+decoded);
    } catch (error) {
        console.error("Error parsing JWT:", error);
        return null;
    }
}

// Logout function
function logout() {
  // Clear token or perform other logout tasks
  sessionStorage.removeItem('jwt'); // Assuming you're using localStorage for the token
  // Prevent user from going back
  history.pushState(null, null, location.href);
  window.onpopstate = function (event) {
    history.go(1);
  }  
  // Redirect to login page (optional)
  window.location.href = './login.html'; // Replace with your actual login page URL
}

load_userProfile=(userDetail)=>{
    fname.innerHTML=" "+userDetail['fname'];
    lname.innerHTML=" "+userDetail['lname'];
    dob.innerHTML=" "+userDetail['dob'];
    contact.innerHTML=" "+userDetail['contact'];

    efname.value=userDetail['fname'];
    elname.value=userDetail['lname'];
    edob.value=userDetail['dob'];
    econtact.value=userDetail['contact'];

}

progressbarRender=()=>{
    var percentage = 0;
    var timer = setInterval(function(){
    percentage = percentage + 20;
    progress_bar_process(percentage, timer);
    }, 2000);
}

function progress_bar_process(percentage, timer)
{   
    progress_bar.style.width=percentage+"%";
    if(percentage==98)
        document.querySelector(".progress-bar").innerHTML="Update Complete";
    if(percentage > 100)
    {   
        clearInterval(timer);
        toggle_view();
        toggle_update();
        progress_bar.style.width="0%";
        document.querySelector('.Logout').style.display="flex";
        hidenotify();
    }
}

function toggle_update(){
    document.querySelector('.back').classList.toggle("show");
    document.querySelector(".EditForm").classList.toggle("show");
    document.querySelector(".progress").classList.toggle("progress_hide");
}