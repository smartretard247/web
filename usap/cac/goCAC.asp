<%
'handle cac
'phase 1 of 2 phase cac authentication
'this phase lookups the userid and hands off in session var to php script to setup login
'phase 2 is the modified process_login.php and it handles the dirty work of session setup
response.write("<title>CAC Login for USAP</title>")
response.redirect("https://gordnfstb003.nase.ds.army.mil/cacProcess.php")
%>

