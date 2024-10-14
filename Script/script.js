var modal = document.getElementById("myModal");

var span = document.getElementsByClassName("close")[0];

var buttons = document.querySelectorAll(".open-modal");

buttons.forEach(function(button) {
    button.onclick = function() {
        var jobID = this.getAttribute("data-id");
        var jobTitle = this.getAttribute("data-title");

        document.getElementById("jobTitle").value = jobID;
        
        // Clear previous values
        // document.getElementById("newStatus").value = '';
        // document.getElementById("newLocation").value = '';
        // document.getElementById("comments").value = '';

        // Show the modal
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