document.addEventListener("DOMContentLoaded", function () {
    fetch('get_user_likes.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const userLikes = data.user_likes;
                Object.keys(userLikes).forEach(postId => {
                    const likeButton = document.getElementById(`like-btn-${postId}`);
                    const dislikeButton = document.getElementById(`dislike-btn-${postId}`);
                    if (userLikes[postId] === 'like') {
                        likeButton.classList.add('active');
                        dislikeButton.classList.remove('active');
                    } else if (userLikes[postId] === 'dislike') {
                        dislikeButton.classList.add('active');
                        likeButton.classList.remove('active');
                    }
                });
            }
        })
        .catch(error => console.error('Error fetching user likes:', error));
    document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const action = this.classList.contains('like-btn') ? 'like' : 'dislike';
            handleLikeDislike(postId, action);
        });
    });
});

function handleLikeDislike(post_id, action) {
    console.log("Button clicked. Post ID:", post_id, "Action:", action);
    fetch('like_dislike_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${post_id}&action=${action}`,
    })
    .then(response => response.json())
    .then(data => {
        console.log("Response from server:", data);
        if (data.status === 'success') {
            document.getElementById(`likes-count-${post_id}`).innerText = data.likes;
            document.getElementById(`dislikes-count-${post_id}`).innerText = data.dislikes;
            // Update button style
            document.getElementById(`like-btn-${post_id}`).classList.toggle('active', action === 'like');
            document.getElementById(`dislike-btn-${post_id}`).classList.toggle('active', action === 'dislike');
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function confirmDelete(post_id) {
    if (confirm("Are you sure you want to delete this post?")) {
        window.location.href = "delete_post.php?post_id=" + post_id;
    }
}


// function handleLikeDislike(postId, action) {
//     const likeCountEl = document.getElementById(`likes-count-${postId}`);
//     const dislikeCountEl = document.getElementById(`dislikes-count-${postId}`);
//     const likeButton = document.getElementById(`like-btn-${postId}`);
//     const dislikeButton = document.getElementById(`dislike-btn-${postId}`);

//     fetch('like_dislike_post.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         body: `post_id=${postId}&action=${action}`
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             likeCountEl.innerText = data.likes;
//             dislikeCountEl.innerText = data.dislikes;
//             if (action === 'like') {
//                 likeButton.classList.add('active');
//                 dislikeButton.classList.remove('active');
//             } else if (action === 'dislike') {
//                 dislikeButton.classList.add('active');
//                 likeButton.classList.remove('active');
//             }
//         } else {
//             alert("Failed to update. Please try again.");
//         }
//     })
//     .catch(error => console.error('Error:', error));
// }

