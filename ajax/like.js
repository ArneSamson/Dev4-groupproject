function updatePromptLikes(button) {
    console.log("updateLikes function called");

    var promptId = button.getAttribute("data-prompt-id");
    var likes = parseInt(button.getAttribute("data-likes")) + 1; // Increment the likes by 1

    // Make an AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "prompts.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response from the server
            console.log(xhr.responseText); // Log the response

            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Update the UI or perform any other actions
                console.log("Likes updated successfully");

                // Get the elements for displaying the like count and button
                var promptCard = button.parentNode;
                var likeCountElement = promptCard.querySelector(".likeCount");
                var likeButtonElement = promptCard.querySelector(".likeButton");

                // Update the like count
                var likes = response.likes;
                var likeText = (likes === 1) ? "1 like" : likes + " likes";
                likeCountElement.textContent = likeText;

                // Update the like button text
                var likeButtonText = (likes === 0) ? "Like" : "Unlike";
                likeButtonElement.innerHTML = likeButtonText;

                // Update the data attribute for the button
                button.setAttribute("data-likes", likes);
            } else {
                console.error("Failed to update likes");
            }
        }
    };

    // Prepare the data to send
    var data = "promptId=" + encodeURIComponent(promptId) + "&likes=" + likes;

    // Send the request
    xhr.send(data);
}
