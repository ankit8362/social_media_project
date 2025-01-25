document.addEventListener("DOMContentLoaded", function () {
    // Attach event listener to all like and dislike buttons
    const buttons = document.querySelectorAll(".like-button, .dislike-button");
    buttons.forEach((button) => {
        button.addEventListener("click", function () {
            const postId = this.getAttribute("data-post-id");
            const action = this.classList.contains("like-button") ? "like" : "dislike";

            // Send AJAX request to like_dislike.php
            fetch("../pages/like_dislike.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `post_id=${postId}&action=${action}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        // Update the counts
                        const likeCount = document.querySelector(`#like-count-${postId}`);
                        const dislikeCount = document.querySelector(`#dislike-count-${postId}`);
                        likeCount.textContent = data.likes;
                        dislikeCount.textContent = data.dislikes;

                        // Update button styles
                        if (action === "like") {
                            this.style.backgroundColor = "green";
                        } else {
                            this.style.backgroundColor = "red";
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
    });
});
