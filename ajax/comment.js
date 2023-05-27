// comment.js

$(document).ready(function() {
    $('#comment-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize(); // Serialize the form data

        $.ajax({
            type: 'POST',
            url: '../php/addComment.php', // Create a separate PHP file to handle the AJAX request
            data: formData,
            success: function(response) {
                // Update the comments section with the new comments
                $('#comments-container').html(response);
            }
        });
    });
});
