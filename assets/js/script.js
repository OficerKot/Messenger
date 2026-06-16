var selectedButtonId = null;
function show_auth() {
  document.getElementById("login-window").style.visibility = "visible";
  document.getElementById("register-window").style.visibility = "hidden";
  setActiveButton("btn-auth");
}

function show_reg() {
  document.getElementById("register-window").style.visibility = "visible";
  document.getElementById("login-window").style.visibility = "hidden";
  setActiveButton("btn-reg");
}

function setActiveButton(id) {
  if (selectedButtonId) {
    document.getElementById(selectedButtonId).classList.remove("active");
  }
  document.getElementById(id).classList.add("active");
  selectedButtonId = id;
}
