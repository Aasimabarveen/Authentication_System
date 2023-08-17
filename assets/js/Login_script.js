var jQueryScript = document.createElement('script');  
jQueryScript.setAttribute('src','https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
document.head.appendChild(jQueryScript);

username=document.querySelector("#uname");
passwrd=document.querySelector("#passwrd");
login=document.querySelector("#Login");
error=document.querySelector(".error");
document.addEventListener('DOMContentLoaded', () => {
  history.pushState(null, null, location.href);
  window.onpopstate = function (event) {
    history.go(1);
  }
});
login.addEventListener("click",(e)=>{
	error.innerHTML="";
	e.preventDefault();
	sessionStorage="";
	if(validate_login())
        if(login_user()===true){
            
        }
});

validate_login=()=>{
	if(username.value==""){
    	set_error("Please enter username","red");
    	return;
    }
    if(passwrd.value==""){
    	set_error("Please enter password","red");
    	return;
    }
    return true;
}

set_error=(msg,color)=>{
	error.innerHTML = msg;
	error.style.display = "block";
	error.style.color=color;
}

login_user=()=>{
    const url = 'http://localhost/authenticate/api/login.php';
    const data = {
        passwrd:passwrd.value,
        username:username.value
    };

    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(data) {
            if (data.message &&data.message.includes("successful")) {
                sessionStorage.setItem('jwt', data.token);
 				username.value="";
 				passwrd.value="";               
                redirectToNextPage();
            }
            else {
                set_error(data.message,"red");
                console.log(data.message);
                 return false;
            } 

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', "", errorThrown);
        }
});
}

 // Function to redirect the user
    function redirectToNextPage() {
        window.location.href = 'profile.html'; // Replace with the URL of your next page
    }
