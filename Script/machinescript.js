let modal = document.getElementById("myModal");

let span = document.getElementsByClassName("close")[0];

let buttons = document.querySelectorAll(".open-modal");

buttons.forEach(function(button) {
    button.onclick = function() {
        let jobID = this.getAttribute("data-id");
        let jobTitle = this.getAttribute("data-title");

        document.getElementById("machineTitle").value = jobID;

        modal.style.display = "block";
    };
});

// When the user clicks on "X", close the modal
span.onclick = function() {
    modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};