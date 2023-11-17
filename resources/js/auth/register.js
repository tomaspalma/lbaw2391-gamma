var email = document.getElementById("email")


email.addEventListener("input", (e) => {
    var temp = document.getElementById("email").value;
    checkEmailExists(temp)
});


function checkEmailExists() {

    console.log("Hello, World")

    //const response = await fetch('../api/api_artists.php?search=' + this.value)
    //const artists = await response.json()

    const section = document.querySelector('#artists')
    section.innerHTML = ''


    
}
