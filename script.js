$(document).ready(function() {
    // This line means: once the HTML page is fully loaded, the function inside will run
    $('#admitForm').on('submit', function(e) {
        e.preventDefault(); // Prevents the form from submitting normally (which would reload the page)

        $.ajax({
            url: 'a.php', // The URL where the data will be sent (in this case, 'a.php')
            type: 'POST', // The method used to send data (POST is typically used for form submissions)
            data: $(this).serialize(), // Takes all the form data and formats it for sending
            success: function(response) { 
                // This function runs if the request is successful
                $('#message').html(response); // Shows the server's response message in the div with id 'message'
            },
            error: function() { 
                // This function runs if there’s an error with the request
                $('#message').html("Error: Could not process the form submission."); // Shows an error message
            }
        });
    });
});
