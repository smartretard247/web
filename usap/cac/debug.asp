<head>
<title>Debugging Forms and Pages</title>
<style>  {Font-Family="Arial"} </style>
<basefont SIZE="2">
</head>

<body>
<h1>Time: <%=now()%></h1>

<h3> ServerVariables Collection </h3>
<% For Each Item in Request.ServerVariables
      For intLoop = 1 to Request.ServerVariables(Item).Count %>
        <% = Item & " = " & Request.ServerVariables(Item)(intLoop) %> <br>
   <% Next 
   Next %>

<h1>Debugging Forms and Pages</h1>

<h3> QueryString Collection </h3>
<% For Each Item in Request.QueryString
      For intLoop = 1 to Request.QueryString(Item).Count %>
        <% = Item & " = " & Request.QueryString(Item)(intLoop) %> <br>
   <% Next 
   Next %>
<h3> Form Collection </h3>
<% For Each Item in Request.Form
      For intLoop = 1 to Request.Form(Item).Count %>
        <% = Item & " = " & Request.Form(Item)(intLoop) %> <br>
   <% Next 
   Next %>
<h3> Cookies Collection </h3>
<% For Each Item in Request.Cookies
      If Request.Cookies(Item).HasKeys Then
         'use another For...Each to iterate all keys of dictionary
         For Each ItemKey in Request.Cookies(Item) %>
            Sub Item: <%= Item %> (<%= ItemKey %>) 
                      = <%= Request.Cookies(Item)(ItemKey)%>
      <% Next 
      Else
         'Print out the cookie string as normal %>
         <%= Item %> = <%= Request.Cookies(Item)%> <br>
   <% End If
   Next %>
<h3> ClientCertificate Collection </h3>
<% For Each Item in Request.ClientCertificate
      For intLoop = 1 to Request.ClientCertificate(Item).Count %>
        <% = Item & " = " & Request.ClientCertificate(Item)(intLoop) %> <br>
   <% Next 
   Next %>
</body>
</html>
