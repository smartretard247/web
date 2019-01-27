<?php $wan_ip = "180.131.239.128"; //for remote router and cam access only ?>

<div class="<?php echo ($annesPage) ? "barcrumbanne" :  "barcrumb"; ?>">
    <table style="width: 200px;">
        <tr>
          <td colspan="2"><h3>Our Sites&nbsp;&nbsp;( <a href="http://192.168.1.254">JEEZY-RTR</a> <a href="https://<?php echo $wan_ip; ?>:8443"> (r)</a> )</h3></td>
        </tr>
        <tr>
          <td style="width: 50%;" valign="top">
            <a href="bills/?db=Bills">Bills</a><br />
            <a href="les/?m=0">LES</a><br />
            <a href="lqm/">LQM</a><br />
            <a href="magic/?new=1&sort=c">Magic</a><br />
            <a href="bills/?db=Property">Property</a><br />
            <a href="shoppinglist">Shop</a><br />
            <a href="SimplySilverAKY">SSA</a><br />
            <a href="https://QuickConnect.to/smartretard247/?launchApp=SYNO.SDS.VideoStation.AppInstance">Video Station</a><br />
            <a href="wow_wotlk">WoW WOTLK</a><br />
          </td>
          <td style="width: 50%;" valign="top">
            <a href="http://www.amazon.com/">Amazon</a> <a href="https://developer.amazon.com/home.html">(dev)</a><br />
            <a href="http://atcc-gns.net:8080/">Bandwidth</a><br />
            <a href="http://www.chase.com/">Chase</a><br />
            <a href="https://github.com/login">GitHub</a><br />
            <a href="http://www.gmail.com/">Gmail</a><br />
            <a href="http://www.hotmail.com">Hotmail</a><br />
            <a href="https://mypay.dfas.mil/mypay.aspx">My Pay</a><br />
            <a href="https://QuickConnect.to/smartretard247">Synology</a><br />
            <a href="http://www.usaa.com/">USAA</a><br />
          </td>
        </tr>
    </table>
</div>
<div class="<?php echo ($annesPage) ? "barcrumbanne" :  "barcrumb"; ?>">
    <table style="width: 200px;">
        <tr>
          <td><h3>Files</h3></td>
        </tr>
        <tr>
            <td valign="top">
                <?php $filesForDL = scandir("files"); $tot_files = sizeof($filesForDL) - 1; ?>

                <?php foreach($filesForDL as $file) : ?>
                    <?php if($file != "." && $file != "..") : ?>
                        <a href="files/<?php echo $file; ?>"><?php echo $file; ?></a><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
</div>