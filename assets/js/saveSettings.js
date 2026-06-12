document
  .getElementById("settingsForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();
    document.getElementById("savingStatus").innerHTML = "Сохранение...";
    let formData = new FormData(this);

    try {
      let response = await fetch("../../profile/saveSettings.php", {
        method: "POST",
        body: formData,
      });
      let result = await response.text();

      setTimeout(() => {
        location.reload();
      }, 1000);
      document.getElementById("savingStatus").innerHTML = result;
    } catch (error) {
      document.getElementById("savingStatus").innerHTML =
        "Ошибка соединения: " + error;
    }
  });
