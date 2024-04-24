const slides = document.querySelector('.slider');
const dots = document.querySelectorAll('.dot');

let index = 0;

dots.forEach((dot, i) => {
dot.addEventListener('click', () => {
index = i;
updateSlider();
});
});

function updateSlider() {
slides.style.transform = `translateX(-${index * 100}%)`;
dots.forEach((dot, i) => {
dot.classList.toggle('active', i === index);
});
}

function nextSlide() {
index = (index + 1) % dots.length;
updateSlider();
}

setInterval(nextSlide, 5000); // Change slide every 5 seconds


const cardContainer = document.querySelector('.card-container');
const leftArrowBtn = document.querySelector('.left-arrow');
const rightArrowBtn = document.querySelector('.right-arrow');

const cards = document.querySelectorAll('.card');
const cardWidth = cards[0].offsetWidth + parseInt(getComputedStyle(cards[0]).marginLeft) * 2;
const cardMargin = 10;

let currentIndex = 0;

// Function to slide the cards to the left
const slideLeft = () => {
currentIndex = (currentIndex - 1 + cards.length) % cards.length;
updateCarousel();
};

// Function to slide the cards to the right
const slideRight = () => {
currentIndex = (currentIndex + 1) % cards.length;
updateCarousel();
};

// Function to update the carousel position
const updateCarousel = () => {
cardContainer.style.transform = `translateX(-${currentIndex * (cardWidth + cardMargin)}px)`;
};

leftArrowBtn.addEventListener('click', slideLeft);
rightArrowBtn.addEventListener('click', slideRight);