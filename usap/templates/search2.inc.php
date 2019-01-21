<html>
<head>
<title>Search USAP</title>
</head>
<body onload="document.search_form.search_text.focus();">
    Search the USAP Database (Military Only)
    <br>
    <form method="get" action="<?php=$_SERVER["SCRIPT_NAME"]?>" name="search_form">
        Search For: <input type="text" name="search_text" size="25"><input type="submit" name="search_submit" value="Search" class="button">
        <br>
        <input type="checkbox" name="sounds_like" value="1"> Sounds Like
    </form>
</body>
</html>