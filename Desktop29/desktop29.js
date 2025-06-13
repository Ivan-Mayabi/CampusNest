//Our two objects, the image and the file input
const file_chosen = document.getElementById("file_chosen");
const file_preview = document.getElementById("file_preview");

//A listener detecting any change to file input
file_chosen.addEventListener("change",function(){
    //If any change, get the first file
    const file = this.files[0];
    if(file){
        //If there is any file, read its content
        const reader= new FileReader();
        //Read the file as a DataURL so that it can be sent to the server
        reader.readAsDataURL(file);
        //Once the file's content has been read, change the content of the image to be what is here
        reader.onload = function(e){
            file_preview.src = e.target.result;
        }
    }
});