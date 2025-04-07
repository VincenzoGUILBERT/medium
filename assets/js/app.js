import '../styles/app.scss';
import '../styles/post.scss';
import '../styles/user.scss';

document.querySelectorAll(".like-button").forEach((button) => {
	button.addEventListener("click", function () {
		let type = this.getAttribute("data-type");
		let id = this.getAttribute("data-id");
		let $likeIcon = this.querySelector("#like-icon");
        let $likeCount = this.querySelector('#like-count');

		fetch(`/like/${type}/${id}`, { method: "POST" }).then((response) => {
			switch (response.status) {
				case 201:
					$likeIcon.className = "like fa-solid fa-heart";
                    $likeCount.textContent++;
					break;
				case 204:
					$likeIcon.className = "like fa-regular fa-heart";
                    $likeCount.textContent--;
					break;
				case 403:
					alert("Please sign in to put a like");
			}
		});
	});
});

document.querySelectorAll("#follow-btn").forEach((button) => {
	button.addEventListener("click", function () {
		let id = this.getAttribute('data-id')
		let $followIcon = this.querySelector("#follow-icon");

		fetch(`/follow/${id}`, { method: "POST" }).then((response) => {
			switch (response.status) {
				case 201:
					$followIcon.className = "fa-solid fa-bookmark";
					break;
				case 204:
					$followIcon.className = "fa-regular fa-bookmark";
					break;
				case 403:
					alert("Please sign up to follow someone");
			}
		});
	});
});