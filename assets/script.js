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
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const showSignup = document.getElementById('showSignup');
    const showLogin = document.getElementById('showLogin');

    showSignup.addEventListener('click', (e) => {
        e.preventDefault();
        loginForm.classList.add('hidden');
        signupForm.classList.remove('hidden');
    });

    showLogin.addEventListener('click', (e) => {
        e.preventDefault();
        signupForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
    });
});

//funksioni per checkbox
function autoSubmit() {
  document.getElementById('filterForm').submit();
}

  //productdetails
  function openTab(evt, tabName) {
    var i, tabContent, tabLinks;
    tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
      tabContent[i].style.display = "none";
    }
    tabLinks = document.getElementsByClassName("tab");
    for (i = 0; i < tabLinks.length; i++) {
      tabLinks[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
  }



function closeModal() {
    const modal = document.querySelector('.modal-cart');
    modal.style.display = 'none';
}
setTimeout(() => {
  const modal = document.querySelector('.modal-cart');
  if (modal) {
      modal.style.display = 'none';
  }
}, 5000);

