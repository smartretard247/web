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
            below have administrator accounts and may be able to assist you with your problem. Please contact someone in the 
            list from your Battalion/Unit</blockquote>
            
            <table border="1" cellspacing="1" cellpadding="1" width="90%" align="center">
              <tr class="table_cheading">
                <th>Unit</th>
                <th>Name</th>
                <th>Email</th>
              </tr>             
              <?
              $names = "HHC,paul.d.piper,GS09,Piper,Paul
              HHC,myra.swan,GS09,Swan,Myra";
              
              $names = str_replace(" ","",$names);

              $lines = explode("\n",$names);

              $old_unit = '';

              foreach($lines as $line)
              {
                  $part = explode(",",$line);
                  
                  echo "<tr><td>{$part[0]}</td><td>{$part[2]} {$part[3]}, {$part[4]}</td><td><a href=\"mailto:{$part[1]}@conus.army.mil?subject=USAP\">{$part[1]}@us.army.mil</a></td></tr>";
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
