const menuToggle = document.querySelector(".menu-toggle");
const navbarLinks = document.querySelector(".navbar-links");

menuToggle.addEventListener("click", () => {
  navbarLinks.classList.toggle("active");
});

/*slider*/
let sliderIndex = 0;

function showSlide(index) {
  const slider = document.getElementById("slider");
  const slides = document.querySelectorAll(".slide");
  const totalSlides = slides.length;

  if (index >= totalSlides) {
    sliderIndex = 0;
  } else if (index < 0) {
    sliderIndex = totalSlides - 1;
  } else {
    sliderIndex = index;
  }
  slider.style.transform = `translateX(-${sliderIndex * 100}%)`;
}

function prevSlide() {
  showSlide(sliderIndex - 1);
}

function nextSlide() {
  showSlide(sliderIndex + 1);
}

setInterval(() => {
  nextSlide();
}, 2000);


//loginsignup
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('.form-box-login');
    const signupForm = document.querySelector('.form-box-signup');

    window.toggleForm = function() {
        if (loginForm && signupForm) {
            loginForm.classList.toggle('hidden');
            signupForm.classList.toggle('hidden');
        } else {
            console.error('Login or Signup form not found in DOM');
        }
    }
});

