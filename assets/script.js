const menuToggle = document.querySelector(".menu-toggle");
const navbarLinks = document.querySelector(".navbar-links");

menuToggle.addEventListener("click", () => {
  navbarLinks.classList.toggle("active");
});

/*slider*/
let sliderIndex = 0;

document.addEventListener("DOMContentLoaded", () => {
  const slider = document.getElementById("slider");
  const slides = document.querySelectorAll(".slide");
  const totalSlides = slides.length;

  if (!slider || slides.length === 0) {
    console.warn("Slider or slides not found");
    return;
  }

  function showSlide(index) {
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
  }, 3000);

  document.querySelector(".prev").addEventListener("click", prevSlide);
  document.querySelector(".next").addEventListener("click", nextSlide);
});


//loginsignup
document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  const showSignup = document.getElementById('showSignup');
  const showLogin = document.getElementById('showLogin');

  if (showSignup && showLogin) {
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
  } 
});

// Validimi i formularit Login
function validateLoginForm() {
  var email = document.getElementById('email-login').value;
  var password = document.getElementById('password-login').value;

  if (email === '' || password === '') {
      alert('All fields must be filled out');
      return false;
  }

  var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailPattern.test(email)) {
      alert('Please enter a valid email');
      return false;
  }

  return true;
}

// Validimi i formularit Sign Up
function validateSignUpForm() {
  var name = document.getElementById('name-signup').value;
  var phone = document.getElementById('phone').value;
  var email = document.getElementById('email-signup').value;
  var password = document.getElementById('password-signup').value;
  var confirmPassword = document.getElementById('confirm-password').value;
  var terms = document.getElementById('terms').checked;

  if (name === '' || phone === '' || email === '' || password === '' || confirmPassword === '') {
      alert('All fields must be filled out');
      return false;
  }

  var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailPattern.test(email)) {
      alert('Please enter a valid email');
      return false;
  }

  var phonePattern = /^\+?[1-9]\d{1,14}$/;
  if (!phonePattern.test(phone)) {
      alert('Please enter a valid phone number');
      return false;
  }

  if (password !== confirmPassword) {
      alert('Passwords do not match');
      return false;
  }

  var passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+={}\[\]:;"'<>,.?/-]{6,20}$/;
  if (!passwordPattern.test(password)) {
      alert('Password should be at least 6 characters long and contain both letters and numbers');
      return false;
  }

  if (!terms) {
      alert('You must agree to the Terms and Conditions');
      return false;
  }

  return true; 
}


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

/*cart */
function checkout() {
  alert("Redirecting to checkout...");
  window.location.href = 'checkout.php';
}
  