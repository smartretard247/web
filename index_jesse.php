<!-- add the jQWidgets framework -->
<link rel="stylesheet" href="../JS/jQWidgets/jqwidgets/styles/jqx.base.css" type="text/css" /><!-- add one of the jQWidgets styles -->
<script type="text/javascript" src="../JS/jQWidgets/scripts/jquery-1.11.1.min.js"></script><!-- add the jQuery script -->
<script type="text/javascript" src="../JS/jQWidgets/scripts/demos.js"></script>
<script type="text/javascript" src="../JS/jQWidgets/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="../JS/jQWidgets/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="../JS/jQWidgets/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="../JS/jQWidgets/jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="../JS/jQWidgets/jqwidgets/globalization/globalize.js"></script>
    
<script type="text/javascript">
  $(document).ready(function () {
    $("#jqxCalendar").jqxCalendar({width: 220, height: 220}); // create jqxcalendar.
    $("#jqxCalendar").bind('valuechanged', function (event) {
      var date = event.args.date;
      var theForm = document.getElementById("calendarForm");
      var theEvent = document.getElementById("calendarEvent");
      var theDate = document.getElementById("calendarDate");
      theForm.style.display="block";
      theEvent.setAttribute("value", "Type event here...");

      var theDateString = date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
      theDate.setAttribute("value", theDateString);
    });
  });
</script>

<?php if($_SESSION['valid_user']) : ?>

<?php $viewingAllEvents = filter_input(INPUT_GET, "all"); ?>

<!--
<div id="headline">
    <?php if($_SESSION['FirstName'] == 'Jesse') : ?>
        <img style="float: left" src="images/logo_j.png" alt="Jeezy's Server"/>
        <img style="float: right" src="images/con_todo.png" alt="With all the love I have..."/>
    <?php else : ?>
        <img style="float: left" src="images/logo_a.png" alt="Jeezy's Server"/>
        <img style="float: right" src="images/con_todo_a.png" alt="With all the love I have..."/>
    <?php endif; ?>
</div>
-->
		
<div id="stylefour">
    <ul>
        <li><a class="current" href="index.php?action=view_home">Home</a></li>
        <li><a href="index.php?action=anne_index">Anne</a></li>
        <li><a href="http://brilliant.org/">Brilliant</a></li>
        <li><a href="https://projecteuler.net/">Project Euler</a></li>
        <li style="padding-left: 10px; padding-right: 10px; padding-top: 5px;">
          <?php include 'view/search_engines.php'; ?>
        </li>

        <?php $db = new Database('server2go');
            $showTable = $db->GetTable('shows', 'Name');
            
            $today = getdate();
            if($today['wday'] == 0) { --$today['wday']; }

            $first = 0;
            $dateToday = date_create_from_format('Y-m-d', date('Y-m-d'));
            foreach ($showTable as $t) {
                $seasonStart = date_create_from_format('Y-m-d', $t['SeasonStart']);
                $seasonEnd = date_create_from_format('Y-m-d', $t['SeasonEnd']);
                date_add($seasonEnd, date_interval_create_from_date_string('3 days'));
              
                if($today['wday'] == ($t['Airs']+1) && $dateToday <= $seasonEnd && $dateToday >= $seasonStart) {
                    if($first) {
                        $showsThatAired .=  ', ' . $t['Name'];
                    } else {
                       $showsThatAired =  'New: ' . $t['Name'];
                       ++$first;
                    }
                }
                
            }
            if($first) { echo '<li><a>' . $showsThatAired. '</a></li>'; }
            
            //get all appointments for the current week
            $endOfWeek = date('Y-m-d', strtotime("this Saturday"));
            $endOfNext = date('Y-m-d', strtotime("next Saturday", strtotime($endOfWeek)));
            $endOfMonth = date('Y-m-d', strtotime("+30 days"));
            $calendarTable = $db->Query("SELECT * FROM calendar WHERE (Complete = '0' AND TheDate <= '$endOfWeek') OR Complete = '0' AND TheDate >= '" . date('Y-m-d') . "' AND TheDate <= '$endOfWeek' ORDER BY TheDate ASC");
            $calendarNext = $db->Query("SELECT * FROM calendar WHERE Complete = '0' AND TheDate > '$endOfWeek' AND TheDate <= '$endOfNext' ORDER BY TheDate ASC");
            
            if($viewingAllEvents) {
              $calendarFuture = $db->Query("SELECT * FROM calendar WHERE Complete = '0' AND TheDate > '$endOfNext' ORDER BY TheDate ASC");
            } else {
              $calendarFuture = $db->Query("SELECT * FROM calendar WHERE Complete = '0' AND TheDate > '$endOfNext' AND TheDate <= '$endOfMonth' ORDER BY TheDate ASC");
            }
            $todaysEvents = '';
         ?>
    </ul>
</div>		
		
<div id="content">
    <div class="help">
        <table align="center" class="noborders" style="width: 100%;">
            <tr>
                <td class="noborders" style="vertical-align: text-top;">
                    <table align="center" style="background: #eee; opacity: 0.2;" onmouseover="style.opacity='1.0';showThumb();" id="firstTable">
                        <tr style="line-height: 14px;">
                          <th id="calendarHeader" style="width: 200px;">Calendar</th>
                          <th style="width: 45%"></th>
                          <th>Current Shows</th>
                        </tr>
                        <tr>
                          <td rowspan="3" style="text-align: center; vertical-align: top;" id="calendarData">
                                <div id="jqxCalendar" style=""></div><br/>
                                <form method="post" action="core/addevent.php" id="calendarForm" style="display: none;">
                                    <input type="hidden" name="DATE" id="calendarDate" size="25"/>
                                    <input type="text" name="EVENT" id="calendarEvent" onfocus="value='';onfocus=''"  size="25"/><br/>
                                    <input type="submit" value="Add to calendar"/>
                                </form>
                          </td>

                          <td rowspan="9" align="center" valign="center">
                            <img id="thumb" src="<?php echo $photoDir . '/' . $image; ?>" style="width: 100%;"/>   
                          </td>

                            <?php if(isset($_POST['addshow'])) {
                                    if($_POST['Name'] != "") {
                                        if($_POST['Airs'] != "") {
                                            if($_POST['IMDBTitleNum'] != "") {
                                                $show->SetName($_POST['Name']);
                                                $show->SetAirs($_POST['Airs']);
                                                $show->SetTitleNum($_POST['IMDBTitleNum']);

                                                if($show->AddTODB() == 0) echo '<p align="center">Error adding show.</p>';
                                            } else echo '<p align="center">IMDB Title Number was empty.</p>';
                                        } else echo '<p align="center">Airs on was empty.</p>';
                                    } else echo '<p align="center">Name was empty.</p>';
                                }

                                if(isset($_POST['removeShow'])) {
                                    if($_POST['ID'] != 0) {
                                        $show->RemoveFromDB($_POST['ID']);
                                    } else echo '<p align="center">No show was selected to remove.</p>';
                                }

                                $showTable = $db->GetTable('shows', 'Name');
                              ?>

                            <td align="center" valign="top">
                              <?php if($showTable) {
                                    foreach ($showTable as $t) {
                                      $seasonStart = date_create_from_format('Y-m-d', $t['SeasonStart']);
                                        
                                      if($dateToday >= $seasonStart) {  
                                        $seasonEnd = date_create_from_format('Y-m-d', $t['SeasonEnd']);
                                        date_add($seasonEnd, date_interval_create_from_date_string('3 days'));
                                        
                                        switch($t['Airs']) {
                                            case 0: $airs = 'Sundays'; break;
                                            case 1: $airs = 'Mondays'; break;
                                            case 2: $airs = 'Tuesdays'; break;
                                            case 3: $airs = 'Wednesdays'; break;
                                            case 4: $airs = 'Thursdays'; break;
                                            case 5: $airs = 'Fridays'; break;
                                            case 6: $airs = 'Saturdays'; break;
                                        }
                                        echo '<a href="https://thepiratebay.org/s/?q=' . $t['Name'] . " " . $t['CurrentEpisode'] . '" target="_blank" title="Airs on ' . $airs . '">' . $t['Name'] . '</a>';
                                        if($dateToday > $seasonEnd) {
                                          echo '&nbsp;<a href="index.php?showid=' . $t['ID'] . '" title="Click to add new season">(over)</a>';
                                        }   
                                        echo '&nbsp;&nbsp;<a href="http://www.imdb.com/title/tt' . $t['IMDBTitleNum'] . '/episodes" target="_blank"><img src="images/imdb.jpg"/></a>';
                                        echo '<br/>';
                                      }
                                    } echo "<br/>";
                                } ?>
                                <br/>
                            </td>
                        </tr>
                        <tr style="line-height: 14px;">
                          <th>Add/Remove Shows</th>
                        </tr>
                        <tr style="height: 50px;">
                          <td style="vertical-align: top;">
                                <form method="post">
                                    <input title="Show Name" type="input" size="8" name="Name"/>
                                    <input title="Airs On (0-6) (Sun-Sat)" type="input" maxlength="1" size="1" name="Airs"/>
                                    <input title="IMDB Title Number" type="input" maxlength="7" size="6" name="IMDBTitleNum"/>
                                    <input title="Add This Show" type="submit" name="addshow" value="+"/>
                                </form><br/>
                                <form method="post">
                                    <select name="ID">
                                        <option value="0">Select Show to Remove</option>

                                        <?php $showTable = $db->GetTable('shows', 'Name');

                                            foreach($showTable as $t) {
                                                echo '<option value="' . $t['ID'] . '">' . $t['Name'] . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <input type="submit" name="removeShow" value="-"/>
                                </form>
                            </td>
                        </tr>
                        <tr style="line-height: 14px;">
                            <th>Upcoming Events <?php if(!$viewingAllEvents) { echo '<a href="?all=1">(view all)</a>'; } ?></th>
                            <th>All Shows</th>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: top;">
                                <?php if($calendarTable) {
                                        $rowCount = 0;
                                        
                                        foreach($calendarTable as $row) {
                                            ++$rowCount;
                                            $idOfEvent = $row['ID'];
                                            $formattedDate = date('M j', strtotime($row['TheDate']));
                                            echo $row['Event'] . ", on <a href='index.php?event=$idOfEvent'>" . $formattedDate . "</a><br/>";
                                            
                                            if($formattedDate == date('M j') && $row['Complete'] == 0) {
                                                $location = "window.location=&#39;index.php?action=eventcomplete&event=" . $row['ID'] . "&#39;";
                                                $snooze = "window.location=&#39;index.php?action=eventsnooze&event=" . $row['ID'] . "&#39;";
                                                $todaysEvents .= '<label><input type="checkbox" onclick="' . $location . ';" />' . $row['Event'] . '</label>&nbsp;&nbsp;&nbsp;<input type="button" value="Snooze" onclick="' . $snooze . ';" /><br/>';
                                            }
                                        }
                                        
                                        if(!$rowCount) {
                                            echo "No upcoming events.";
                                        }
                                    } ?>
                            </td>
                            <td rowspan="9" style="vertical-align: top;">
                              <?php $allShows = $db->GetTable('shows', 'Name');
                                  if($allShows) {
                                    foreach ($allShows as $t) {
                                      switch($t['Airs']) {
                                          case 0: $airs = 'Sundays'; break;
                                          case 1: $airs = 'Mondays'; break;
                                          case 2: $airs = 'Tuesdays'; break;
                                          case 3: $airs = 'Wednesdays'; break;
                                          case 4: $airs = 'Thursdays'; break;
                                          case 5: $airs = 'Fridays'; break;
                                          case 6: $airs = 'Saturdays'; break;
                                      }
                                      echo '<a href="https://thepiratebay.org/s/?q=' . $t['Name'] . '" target="_blank" title="Airs on ' . $airs . '">' . $t['Name'] . '</a>';
                                      echo '&nbsp;<a href="index.php?showid=' . $t['ID'] . '" title="Click to edit">(e)</a>';
                                      echo '&nbsp;&nbsp;<a href="http://www.imdb.com/title/tt' . $t['IMDBTitleNum'] . '/episodes" target="_blank"><img src="images/imdb.jpg"/></a>';
                                      echo '<br/>';
                                    }
                                } ?>
                                <br/>
                            </td>
                        </tr>
                        <tr style="line-height: 14px;">
                            <th>Events Next Week</th>
                        </tr>
                        <tr>
                            <td style="text-align:center; vertical-align: top;">
                                <?php if($calendarNext) {
                                        $rowCount = 0;
                                        foreach($calendarNext as $row) {
                                            ++$rowCount;
                                            $idOfEvent = $row['ID'];
                                            $formattedDate = date('M j', strtotime($row['TheDate']));
                                            echo $row['Event'] . ", on <a href='index.php?event=$idOfEvent'>" . $formattedDate . "</a><br/>";
                                        }
                                        if(!$rowCount) {
                                            echo "No events next week.";
                                        }
                                    }  ?>
                            </td>
                        </tr>
                        
                        <?php 
                          $rowCount = 0;
                          $strFutureEvents = "";
                          if($calendarFuture) {
                            foreach($calendarFuture as $row) {
                                ++$rowCount;
                                $idOfEvent = $row['ID'];
                                $formattedDate = date('M j', strtotime($row['TheDate']));
                                $strFutureEvents .= $row['Event'] . ", on <a href='index.php?event=$idOfEvent'>" . $formattedDate . "</a><br/>";
                            }
                          }
                          if($rowCount > 0) : ?>
                            <tr style="line-height: 14px;">
                              <th>Future Events</th>
                            </tr>
                            <tr>
                              <td style="text-align:center; vertical-align: top; height: auto;">
                                <?php echo $strFutureEvents; ?>
                              </td>
                            </tr>
                          <?php endif; ?>
                    </table>
                </td>
                <td class="noborders" id="rightbar">
                    <table class="noborders" style="opacity: 0.2;" onmouseover="style.opacity='1.0';">
                        <tr>
                            <td class="noborders">
                                <?php include 'view/references.php'; ?>
                                <?php include 'view/oursites.php'; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<table id="rollingTable"></table>

<div id="footer" style="background-color: black;">
    <a href="../core/logout.php">Logout</a> | Copyright by Jeezy
</div>

<?php if($todaysEvents != '') : //call setEventAlert(events) ?>
    <script type="text/javascript">setEventAlert('<br/><?php echo $todaysEvents; ?><br/><br/>');</script>
<?php endif; ?>

</div>
<?php else : ?>
    <b id="error">&nbsp;&nbsp;You do not have permission to view this site.</b><br/>
<?php include '/view/rightbar.php'; endif;