<html>
<head>
<title>USAP Help Page</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css.php">
</head>

<body>
  <table width="70%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td>
      <p align="center"><font size="7"><b><i>USAP</i></b></font></p>
      <p align="center"><font size="5"><strong>Unit Soldier Administration Program</strong></p>
      <table width="90%" border="1" cellspacing="2" cellpadding="2" align="center">
        <tr>
          <td class="table_cheading">
            USAP Help Page
          </td>
        </tr>
        <tr>
          <td>
            <blockquote>Please direct all questions or requests for help to your Battalion IMO. The personnel listed
            below have administrator accounts and may be able to help you with your problem. Please contact someone on the 
            list from your respective Battalion/Unit</blockquote>
            
            <table border="1" cellspacing="1" cellpadding="1" width="90%" align="center">
              <tr class="table_cheading">
                <th>Unit</th>
                <th>Name</th>
                <th>Email</th>
              </tr>             
              <?
              $names = "HHC,graves,SSG,Graves,Ravunda
              HHC,Meeler,Annice
              73rd,SFC,McGaha,Anthony
              369th,SSG,Gray,Ralph
              447th,MSG,Newsome,Joe
              551st,SSG,Brawner,John B.";
              
              $names = str_replace(" ","",$names);

              $lines = explode("\n",$names);

              $old_unit = '';

              foreach($lines as $line)
              {
                  $part = explode(",",$line);
                  
                  echo "<tr><td>{$part[0]}</td><td>{$part[2]} {$part[3]}, {$part[4]}</td><td><a href=\"mailto:{$part[1]}@gordon.army.mil\">{$part[1]}@gordon.army.mil</a></td></tr>";
              }

              ?>
            </table>
            <br>
            <br>
          </td>
        </tr>
        <tr class="table_cheading">
          <td>
            <a href="javascript:history.go(-1);">Back</a>
          </td>
        </tr>
      </table>
      <br>
    </td>
  </tr>
</table>
</body>
</html>
