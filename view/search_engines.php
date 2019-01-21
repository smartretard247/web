<script type="text/javascript">
  $(document).ready(function () {
    document.getElementById("searchText").addEventListener("keyup", function(event) {
      event.preventDefault();
      if (event.keyCode === 13) {
        if(event.shiftKey) {
          document.getElementById("engine").value = "Torrent";
          document.getElementById("go").click();
        } else if(event.ctrlKey) { 
          document.getElementById("engine").value = "Translate";
          document.getElementById("go").click();
        } else {
          document.getElementById("go").click();
        }
      }
    });

    document.getElementById("engine").addEventListener("keyup", function(event) {
      event.preventDefault();
      if (event.keyCode === 13) {
        document.getElementById("go").click();
      }
    });
  });

  function googleit() {
    var googleBar = document.getElementById("googleit");
    googleBar.q.value = document.getElementById("searchText").value;
    googleBar.submit();
  }

  function torrentit() {
    var torrentBar = document.getElementById("torrent");
    torrentBar.q.value = document.getElementById("searchText").value;
    torrentBar.submit();
  }

  function translate() {
      var sentence = document.getElementById("searchText").value;
      var link = "https://translate.google.com/#en/ja/" + sentence;
      window.location.href = link;
  };

  function phpit() {
    var sentence = document.getElementById("searchText").value;
      var link = "http://jp2.php.net/results.php?q=" + sentence + "&l=en&p=all";
      window.location.href = link;
  }

  function appledevit() {
    var sentence = document.getElementById("searchText").value;
      var link = "https://developer.apple.com/search/?q=" + sentence;
      window.location.href = link;
  }

  function youtubeit() {
    var sentence = document.getElementById("searchText").value;
      var link = "https://www.youtube.com/results?search_query=" + sentence;
      window.location.href = link;
  }

  function amazonit() {
    var sentence = document.getElementById("searchText").value;
      var link = "https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=" + sentence;
      window.location.href = link;
  }

  function gameGuide(num) {
    var sentence = document.getElementById(num).value + " " + document.getElementById("searchText").value;
    var googleBar = document.getElementById("googleit");
    googleBar.q.value = sentence;
    googleBar.submit();
  }

  function search() {
      var engineId = document.getElementById("engine");
      var selection = engineId.options[engineId.selectedIndex].text;
      var value = document.getElementById("engine").value;

      switch(selection) {
        case "Amazon": amazonit(); break;
        case "Apple Dev": appledevit(); break;
        case "Google": googleit(); break;
        case "PHP.net": phpit(); break;
        case "Torrent": torrentit(); break;
        case "Translate": translate(); break;
        case "YouTube": youtubeit(); break;
        default: gameGuide(value);
      }
  };
</script>
  
<?php $games = $db->Query("SELECT * FROM games ORDER BY Name ASC"); ?>
<?php
  $aGames = array();
  foreach($games as $game) {
    $id = $game['ID'];
    $name = $game['Name'];
    echo '<input id="' . $id . '" type="hidden" value="' . $name . '"/>';
    $nextGame = array('ID' => $id, 'Name' => $name);
    array_push($aGames, $nextGame);
  }
?>

  <form method="get" action="https://thepiratebay.org/s/" id="torrent" hidden>
    <input type="text" name="q" hidden>
  </form>
  <form method="get" action="http://www.google.com/search" name="googleit" id="googleit" hidden>
    <input type="text" id="q" name="q" maxlength="255" hidden/>
  </form>
  
  <input title="Shift & enter to perform torrent search&#013;Control & enter to translate to Japanese" size="20" type="text" id="searchText" maxlength="255" value="" autofocus onkeydown="document.getElementById('firstTable').style.opacity='1.0';" onsubmit="search();"/>
  <select id="engine" style="width: 100px;">
    <optgroup label="--Other--">
      <option>Translate</option>
    </optgroup>
    <optgroup label="--Search Engines--">
      <option>Amazon</option>
      <option>Apple Dev</option>
      <option selected>Google</option>
      <option>Torrent</option>
      <option>PHP.net</option>
      <option>YouTube</option>
    </optgroup>
    <optgroup label="--Game Guides--">
      <?php foreach($aGames as $game) {
        echo '<option value="' . $game['ID'] . '">' . $game['Name'] . '</option>';
      } ?>
    </optgroup>
  </select>
  <input id="go" type="button" value="Go" onclick="search();"/>