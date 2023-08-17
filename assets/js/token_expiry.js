// Function to check if the token has expired
function isTokenExpired(tokenexp) {
  const currentTime = Date.now() / 1000;
  console.log("token expiry "+tokenexp);
  if (tokenexp < currentTime) {
  	console.log("token expiry "+tokenexp+" false "+currentTime);
  	return true;
  } else {
  	console.log("token expiry "+tokenexp+" true "+currentTime);
  	  return false;
  }
}