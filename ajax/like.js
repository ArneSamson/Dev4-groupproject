function updatePromptLikes(button) {
  var promptId = button.getAttribute("data-prompt-id");
  var likes = parseInt(button.getAttribute("data-likes")); // Get the current likes

  // Check if the user has already liked the prompt
  var hasLiked = button.classList.contains("liked");

  // Toggle the like status
  if (hasLiked) {
    likes -= 1; // Decrement the likes
    button.classList.remove("liked"); // Remove the "liked" class
  } else {
    likes += 1; // Increment the likes
    button.classList.add("liked"); // Add the "liked" class
  }

  // Prepare the data to send
  var data = "promptId=" + encodeURIComponent(promptId) + "&likes=" + likes;

  // Make a fetch request to the server
  fetch("prompts.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: data,
  })
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Failed to update likes");
      }
    })
    .then((response) => {
      // Handle the response from the server
      console.log(response); // Log the response

      if (response.success) {
        // Update the UI or perform any other actions
        console.log("Likes updated successfully");

        // Update the like count and button text
        var promptCard = button.parentNode;
        var likeCountElement = promptCard.querySelector(".likeCount");

        // Update the like count
        var likes = response.likes;
        var likeText = likes === 1 ? "1 like" : likes + " likes";
        likeCountElement.textContent = likeText;
        //if like count is -1 then set it to 0
        if (likes < 0) {
          likeCountElement.textContent = "0 likes";
        }

        // Update the button text based on the like status
        if (likes >= 0) {
          button.textContent = hasLiked ? "Like" : "Unlike";
        } else {
          console.error("Failed to update likes");
        }
      } else {
        console.error("Failed to update likes");
      }
    })
    .catch((error) => {
      console.error(error);
    });
}
