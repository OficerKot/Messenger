window.messageView = new MessageView();

// ============ события PostView
messageView.on("delete", (msgId) => {
  MessageApi.deleteMsg(msgId);
});

messageView.on("edit", (msgId) => {
  messageView.showMsgEditForm(msgId, MessageApi.saveEditMsg);
});

// ==================================
// Функция для получения GET параметров из URL
function getUrlParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

// ===========================================
const other_user_id = getUrlParam("user_id");
MessageApi.loadMessages(other_user_id);

const msgForm = document.getElementById("msg-form");
if (msgForm) {
  msgForm.addEventListener("submit", async (e) => MessageApi.sendMsg(e));
}
