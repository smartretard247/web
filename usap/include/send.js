function send() {
  document.login.password.value = encode64(document.login.password.value);
  document.login.submit();}