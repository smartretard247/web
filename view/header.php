<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <!-- the head section -->
<head>
  <meta charset="utf-8"/>
    <title>StawberryFountain.net</title>
    <link rel="stylesheet" type="text/css" href="../CSS/htdocs.css" />
    
    <style type="text/css">
      #thumb {
        width: <?php echo $width; ?>px;
        visibility: hidden;

        transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d; /* Chrome, Safari, Opera */
        transition: 1s;
        -webkit-transition: 1s; /* Safari */
      }
    </style>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
    <script type="text/javascript" src="../JS/utils.js"></script>
    <script type="text/javascript" src="../JS/zoom-thumb.js"></script>
    
    <script type="text/javascript">
      function showThumb() {
        var thumb = document.getElementById("thumb");
        thumb.style.visibility = "visible";
      }
        
      function setEventAlert(to) {
          var rollingTable = document.getElementById('rollingTable');

          if(rollingTable) {
              var row = rollingTable.insertRow(0);
              var cell = row.insertCell(0);
              cell.colSpan = '2';
              cell.style.verticalAlign = 'top';
              cell.style.fontSize = '14px';
              cell.innerHTML = to.toString();

              //top for event header
              row = rollingTable.insertRow(0);

              cell = document.createElement('th');
              cell.style.fontSize = '18px';
              cell.style.verticalAlign = 'middle';
              cell.innerHTML = "**Don't Forget**";
              row.appendChild(cell);

              cell = document.createElement('th');
              cell.style.fontSize = '14px';
              cell.style.verticalAlign = 'middle';
              cell.innerHTML = '<input type="button" onclick="rollTable();" value="X" />';
              row.appendChild(cell);

              var firstTable = document.getElementById('firstTable');
              rollingTable.addEventListener( 'mouseover', function() { firstTable.style.opacity = '0.4'; });

              window.addEventListener('DOMContentLoaded', rollTable, false);
          }
      };

        var rollTable = function() {
            var rollingTable = document.getElementById('rollingTable');
            if(rollingTable) {
                var content = document.getElementById('content');
                content.toggleClassName('disabled');
                rollingTable.toggleClassName('rolled');
            }
        };

        window.onload=function() {
            var rollingTable = document.getElementById('rollingTable');
            if(rollingTable) {
                document.googleit.q.focus(); //this isn't on this page
            }
        };
    </script>

    <script type="text/javascript">
        function onloadFocus(){
            setTimeout(function() {
                document.getElementById('searchText').focus();
            }, 10);
        }
    </script>
    
    

    
</head>
