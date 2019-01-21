<?php if($_SESSION['valid_user']) : ?>
  <?php $annesPage = true; ?>
        
<div id="stylefour">
    <ul>
        <li><a href="index.php?action=view_home">Home</a></li>
        <li><a class="current" href="index.php?action=anne_index">Anne</a></li>
        <li><a href="http://www.youtube.com">YouTube</a></li>
        <li style="padding-left: 10px; padding-right: 10px; padding-top: 5px;">
          <?php include 'view/search_engines.php'; ?>
        </li>
    </ul>
</div>		
		
<div id="contentanne"><?php
       $birth = '2012-08-02';

       $d=date("w");
       $t=date("W");
       $t-=32;
       if($d==4) { 
           $outstr='Mason is ';
           $outstr.=$t+1;
           $outstr.=' Week'; 
           if($t+1!=1) { $outstr .= 's'; }
           $outstr.=' old Today!'; 
       } else {
           if ($d==0) { 
               $t+=1; 
           }

           if ($d>=5) {
               $t+=1;
               $d-=7;
           } 

           if ($d<5) {
               $d+=3;
           }

           $outstr='Mason is ';
           $outstr.=$t;
           $outstr.=' Week';
           if($t!=1) { $outstr .= 's'; }
           $outstr.=' and ';
           $outstr.=$d;
           $outstr.=' Day';

           if($d!=1) { $outstr .= 's'; }
           $outstr .= ' old!';
       }; ?>
    
    <div class="help">
        <table align="center" class="noborders" style="width: 95%">
            <tr>
                <td class="noborders">
                    <table align="center" style="" onmouseover="showThumb();" id="firstTable" class="noborders">
                        <tr>
                          <td style="width: auto; text-align: center;" class="noborders">
                                <ul>
                                  <a href="http://www.facebook.com" target="_blank"><img width="50" height="50" src="images/facebook.png" alt="Facebook"/></a>
                                  <a href="http://www.amazon.com" target="_blank"><img width="50" height="50" src="images/amazon.jpg" alt="Amazon"/></a>
                                </ul>
                                <ul>
                                  <a href="http://www.foxyfix.com" target="_blank"><img width="50" height="50" src="images/foxy.png" alt="Foxy Fix"/></a>
                                  <a href="http://www.meandmybigideas.com" target="_blank"><img width="50" height="50" src="images/mambi.jpg" alt="MAMBI"/></a>
                                </ul>
                                <ul>
                                  <a href="http://www.victoriassecret.com" target="_blank"><img width="50" height="50" src="images/victoria2.jpg" alt="Victoria's Secret"/></a>
                                  <a href="http://www.etsy.com" target="_blank"><img width="50" height="50" src="images/etsy.png" alt="Etsy"/></a>
                                </ul>
                                <ul>
                                  <a href="http://www.pintrest.com" target="_blank"><img width="50" height="50" src="images/pintrest.png" alt="Pintrest"/></a>
                                  <a href="http://www.target.com" target="_blank"><img width="50" height="50" src="images/target.png" alt="Target"/></a>
                                </ul>
                                <ul>
                                  <a href="http://www.chase.com" target="_blank"><img width="50" height="50" src="images/chase.png" alt="Chase"/></a>
                                  <a href="http://www.usaa.com" target="_blank"><img width="50" height="50" src="images/usaa2.png" alt="USAA"/></a>
                                </ul>
                                <ul>
                                  <a href="http://mail.yahoo.com" target="_blank"><img width="50" height="50" src="images/yahoo.jpg" alt="Yahoo!"/></a>
                                  <a href="http://www.paypal.com" target="_blank"><img width="50" height="50" src="images/paypal.jpg" alt="PayPal"/></a>
                                </ul>
                            </td>

                            <td class="noborders" rowspan="9" align="center" valign="center" style="width: 50%;">
                                <img id="thumb" src="<?php echo $photoDir . '/' . $image; ?>" style="width: 95%;"/>
                            </td>

                            <td class="noborders"><?php $imgWidth = 250; $imgHeight = 62; ?>
                              <a href="http://www.thecoffeemonsterzco.com" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/coffee.jpg" alt="Coffee Monsterz Co."/></a><br/><br/>
                              <a href="http://www.pearteapaperie.com" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/pear.png" alt="Pearteapaperie"/></a><br/><br/>
                              <a href="http://www.citygirlplanners.com" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/citygirl.jpg" alt="City Girl Planners"/></a><br/><br/>
                              <a href="http://www.bathandbodyworks.com/" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/bbw.jpg" alt="Bath and Body Works"/></a><br/><br/>
                              <a href="http://www.the1407planners.com" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/1407.jpg" alt="1407 Planners"/></a><br/><br/>
                              <a href="http://www.oldnavy.com" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/oldnavy.png" alt="Old Navy"/></a><br/><br/>
                              <a href="http://www.forever21.com" target="_blank"><img width="<?php echo $imgWidth; ?>" height="<?php echo $imgHeight; ?>" src="images/f21.jpg" alt="Forever 21"/></a><br/><br/>
                                
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="noborders" id="rightbar">
                  <table class="noborders">
                    <tr>
                      <td class="noborders">
                            <?php include 'view/oursites.php'; ?>
                        </td>
                    </tr>
                  </table>
                </td>
            </tr>
            <tr>
              <td colspan="2" style="font-size: 26px; color: #cc3300; font-weight: bold; text-align: center; background-color: #eee; padding: 10px;">
                The beginning of forever! I Love You!
              </td>
            </tr>
            <tr>
                <td align="justify" colspan="2" style="background: #eee;">
                    "I just want you to know you are an amazing wife. You always take care of me and I love you for it! 
                    I am so excited for the future with you and the fun times ahead. We are a great couple and I am 
                    absolutely sure I made the right decision in choosing my wife. I love you with all my heart and I hope you know it!"
                </td>
            </tr>
        </table>
    </div>
</div>

<div id="footer">
    <a href="../core/logout.php">Logout</a> | Copyright by Jeezy
</div>
		
<frameset rows="100%">
        <frame src="http://www.google.com"/>
</frameset>
		
		<div align="center">
<!--			<img id="pic" src="images/slide/<?php echo $image; ?>"/>  -->
		</div>
	</div>
<?php else : ?>
    <b id="error">&nbsp;&nbsp;You do not have permission to view this site.</b><br/>
<?php include '/view/rightbar.php'; endif; ?>