async function updateMessages() {
  try {
    const response = await fetch("../handlers/check_messages.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "last_id=" + encodeURIComponent(lastId),
    });

    const data = await response.json();

    if (!data.success) {
      return;
    }
  } catch (error) {
    console.error("Polling error:", error);
  }
}

updateMessages();

setInterval(updateMessages, 3000);
