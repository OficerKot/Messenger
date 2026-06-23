document.getElementById("menuBtn").addEventListener("click", function () {
  leftMenu();
});

function leftMenu() {
  const menu = document.querySelector(".left-panel");
  if (menu) {
    menu.classList.toggle("open");
  }
}
