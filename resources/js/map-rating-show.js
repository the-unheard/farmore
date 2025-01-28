// JavaScript to handle hover and click behavior for stars
const stars = document.querySelectorAll('.star');
const ratingText = document.getElementById('rating-text');
const ratingInput = document.getElementById('rating');
const form = document.getElementById('rating-form');
const descriptions = ["Doubtful", "Questionable", "Average", "Reliable", "Legit"];

stars.forEach(star => {
    // Handle hover (mouseover)
    star.addEventListener('mouseover', () => {
        const ratingValue = star.getAttribute('data-value');
        updateStars(ratingValue);
        updateRatingText(ratingValue);
    });

    // Handle click to submit rating
    star.addEventListener('click', () => {
        ratingInput.value = star.getAttribute('data-value');
        form.submit(); // Auto-submit when a star is clicked
    });

    // Handle mouseout to reset stars
    star.addEventListener('mouseout', () => {
        const currentRating = ratingInput.value || 0;
        updateStars(currentRating); // Reset stars to current rating
        updateRatingText(currentRating);
    });
});

// Update the stars (fill them based on hover or selected rating)
function updateStars(ratingValue) {
    stars.forEach(star => {
        const value = star.getAttribute('data-value');
        if (value <= ratingValue) {
            star.classList.remove('text-gray-400');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-400');
        }
    });
}

// Update the rating text on the right based on the hovered or selected rating
function updateRatingText(ratingValue) {
    if (ratingValue > 0) {
        ratingText.textContent = descriptions[ratingValue - 1];
    } else {
        ratingText.textContent = '';
    }
}
