window.messageView = new MessageView();
window.messageApi = new MessageApi();

// ============ события PostView
messageView.on("delete", (msgId) => {
  messageApi.deleteMsg(msgId);
});

messageView.on("edit", (msgId) => {
  messageView.showMsgEditForm(msgId, messageApi.saveEditMsg);
});

// ==================================
// Функция для получения GET параметров из URL
function getUrlParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}
// ===========================================

const other_user_id = getUrlParam("user_id");
messageApi.loadMessages(other_user_id);

const msgForm = document.getElementById("msg-form");
if (msgForm) {
  msgForm.addEventListener("submit", async (e) => messageApi.sendMsg(e));
}

messageApi.startPolling(other_user_id);
document.addEventListener("visibilitychange", function () {
  if (document.hidden) {
    console.log("Вкладка скрыта");
    messageApi.stopPolling();
  } else {
    console.log("Вкладка активна");
    messageApi.startPolling(other_user_id);
  }
});
