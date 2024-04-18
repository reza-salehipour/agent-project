document.querySelector("html").classList.add('js');

var fileInput  = document.querySelector( ".input-file" ),
    button     = document.querySelector( ".input-file-trigger" ),
    the_return = document.querySelector(".file-return");

button.addEventListener( "keydown", function( event ) {
    if ( event.keyCode == 13 || event.keyCode == 32 ) {
        fileInput.focus();
    }
});
button.addEventListener( "click", function( event ) {
    fileInput.focus();
    return false;
});
fileInput.addEventListener( "change", function( event ) {
    the_return.innerHTML = this.value;
    document.getElementById("submit-button").style.display = "block";
});

setTimeout(()=>{
    const collection= document.getElementsByClassName("session-section");
    for (let i = 0; i < collection.length; i++) {
        collection[i].style.display="none";
    }
}, 4000)
