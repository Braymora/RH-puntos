const iconEyes = document.querySelector(".icon");
const iconEyesTwo = document.querySelector(".icon-two");
const input = document.getElementById("input");

const iconTwoEyes = document.querySelector(".iconTwo");
const iconTwoConfirm = document.querySelector(".icon-two__confirm");
const inputTwo = document.getElementById("inputTwo");

iconEyes.addEventListener("click", function () {
  if (input.type === "password") {
    input.type = "text";
    iconEyes.style.display = "none";
    iconEyesTwo.style.display = "block";
  } else {
    input.type = "password";
    iconEyes.style.display = "none";
    iconEyesTwo.style.display = "block";
  }
});

iconEyesTwo.addEventListener("click", function () {
  if (input.type === "text") {
    input.type = "password";
    iconEyes.style.display = "block";
    iconEyesTwo.style.display = "none";
  }
});


iconTwoEyes.addEventListener("click", function () {
  if (inputTwo.type === "password") {
    inputTwo.type = "text";
    iconTwoEyes.style.display = "none";
    iconTwoConfirm.style.display = "block";
  } else {
    inputTwo.type = "password";
    iconTwoEyes.style.display = "none";
    iconTwoConfirm.style.display = "block";
  }
});

iconTwoConfirm.addEventListener("click", function () {
  if (inputTwo.type === "text") {
    inputTwo.type = "password";
    iconTwoEyes.style.display = "block";
    iconTwoConfirm.style.display = "none";
  }
});

