var jQueryScript = document.createElement('script');  
jQueryScript.setAttribute('src','https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
document.head.appendChild(jQueryScript);

fname=document.querySelector("#fname");
lname=document.querySelector("#lname");
dob=document.querySelector("#dob");
contact=document.querySelector("#contact");
username=document.querySelector("#username");
register=document.querySelector("#register");
passwrd=document.querySelector("#passwrd");
cpasswrd=document.querySelector("#cpasswrd");
error=document.querySelector(".error");



register.addEventListener("click",(e)=>{
	error.innerHTML="";
	e.preventDefault();
	if(validate_registration())
        if(register_user()===true){
            
        }
});

validate_registration=()=>{
	var ToDate = new Date();
	var p=/^[0-9]]{10}$/;
    if (!(new Date(dob.value) < ToDate)) {
		set_error("Please Enter Valid DoB","red");    
		return;	
     }
    if(parseInt(contact.value.length)!=10){
     	set_error("Please Enter Valid Phone Number "+contact.value.length+","+"red");    	
     	return;
    }
    if(!(passwrd.value===cpasswrd.value)){
     	set_error("Password, confirm Password Mismatch","red");    	
     	return;
    }
    if(passwrd.value.length<8){
    	set_error("Password Length should be altleast 8 characters","red");
    	return;
    }
    if(p.test(contact.value)) {
		set_error("Please Enter Valid Phone Number","red");
        return;   	
     }
  	return true;	
}

set_error=(msg,color)=>{
	error.innerHTML = msg;
	error.style.display = "block";
	error.style.color=color;
}

clear_data=()=>{
    fname.value="";
    lname.value="";
    dob.value="";
    contact.value="";
    passwrd.value="";
    username.value="";
    cpasswrd.value="";
}
register_user=()=>{
    console.log("came to register");
    const url = 'http://192.168.56.1/authenticate/api/register.php';
    const data = {
        fname: fname.value,
        lname: lname.value,
        dob:dob.value,
        contact:contact.value,
        passwrd:passwrd.value,
        username:username.value
    };

    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(data) {
            if (data.message &&data.message.includes("Succesful")) {
                set_error(data.message,"green");
                console.log(data.message);
                clear_data();
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
