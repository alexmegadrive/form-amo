
var modal = document.getElementById('modal-wrapper');
var span = document.getElementsByClassName("close")[0]; 

var btn = document.getElementById("myBtn");

var span = document.getElementsByClassName("close")[0]; 
btn.onclick = function() {
    modal.style.display = "block";
    //modal.classList.add('active'); 
//
}

span.onclick = function() {
    modal.style.display = "none";
}
// span.onclick = function() {
//     modal.style.display = "none";
// }
window.onclick = function(event) {
    if (event.target == modal) {
         modal.style.display = "none";
      // modal.classList.remove('active'); 

    }
}