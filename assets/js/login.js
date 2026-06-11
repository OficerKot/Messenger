document
  .getElementById("loginForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();
    document.getElementById("authStatus").innerHTML = "Вход...";
    //this - форма, в которой произошёл event
    let formData = new FormData(this);

    try {
      let response = await fetch("../../auth/login.php", {
        method: "POST",
        body: formData,
      });
      let result = await response.text();
      document.getElementById("authStatus").innerHTML = result;

      if (!result.includes("Ошибка")) {
        setTimeout(() => {
          window.location.href = "../profile/wall.php";
        }, 1500);
      }
    } catch (error) {
      document.getElementById("authStatus").innerHTML =
        "Ошибка соединения: " + error;
    }
  });
