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
            below have administrator accounts and may be able to help you with your problem. Please contact someone in the 
            list from your Battalion/Unit</blockquote>
            
            <table border="1" cellspacing="1" cellpadding="1" width="90%" align="center">
              <tr class="table_cheading">
                <th>Unit</th>
                <th>Name</th>
                <th>Email</th>
              </tr>             
              <?
              $names = "HHC,gravesr,SGT,Graves,Ravunda
              HHC,graydc,SFC,Gray,Daniel
              HHC,meelera,GS11,Meeler,Annice
              73rd,braschd,SFC,Brasch,David
              73rd,rother,SSG,Rothe,Richard
              73rd,millwart,SFC,Millward,Thomas
              73rd,medelh,SSG,Medel,Henry
              369th,grayr,SSG,Gray,Ralph
              369th,dutramc,SSG,Dutram, Cristen
              369th,andersoh,SSG,Anderson,Hardy
              447th,heflinaw,SSG,Heflin,Aaron
              447th,hollidam,SFC,Holliday,Markell
              551st,murdenp,SFC,Murden,Patrica
              551st,swainfl,SSG,Swain, Fred
              551st,tayloran,SSG,Taylor,Andre
              551st,thomasaa,SFC,Thomas, Avis
              551st,hunterr,1LT,Hunter, Richard";

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
