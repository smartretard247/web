<?
//configuration file
include("../lib-common.php");
//admin functions
include("lib-admin.php");

echo com_siteheader("Administration Section");

//ensure user has permission to view
//this page. if not, show error and exit.
if(!check_permission(25))
{
    echo "Unauthorized Access: You do not have permission to access this area";
    echo com_sitefooter();
    exit();
}

echo admin_menu();
?>
<p>&nbsp;</p>
<table width="80%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr class="table_heading">
    <td>Admin Message</td>
  </tr>
  <tr>
    <td>
      <p>
      13JAN02:
        <blockquote><strong>New Permission Added:</strong> Permission 32 is "View/Edit Restricted
      Remark." This permission should only be given out to Commanders and 1SG/CSM. Anyone else requesting
      this permission should be approved by the Battalion Commander or a delegate. Administrators DO NOT 
      need this permission, keep your nose out of the remarks, please.<font size="-1"><i>Submitted by
      1LT Holmes</i></font></blockquote>
      </p>
      
      <p>
      08OCT02:
        <blockquote><strong>New Permission Added:</strong> Permission 29 is "Add/Edit/Delete Driver".
      This is a permission for Master Drivers to enter license information on soldiers. It will track
      the permit and license expiration, among other things. This information has not been added to the 
      View Soldier page and a report to poll this data still needs to be made. <font size="-1"><i>
      Submitted by 1LT Holmes</i></font></blockquote>
      </p>
      <p>
      27SEP02: 
        <blockquote><strong>New Permission Added:</strong> Permission 28 is 'Add Special'. This will
      allow the user to use a special submission form to submit a new soldier/civilian into the database
      for the units that are checked. The input only requires the first and last name to be given. If no
      SSN is given, a 5 digit number is assigned. <strong>NOTICE:</strong> This should be used very
      sparingly. Without all of the information on these users, you will not be able to edit the user and
      they may not show up on some reports. Some queries may break because of the missing data, also. This
      is meant to be a last resort to get the basic info about a soldier/civilian into the database
      so the numbers are correct.<font size="-1"><i>Submitted by 1LT Holmes</i></font></blockquote>
      </p>
      <p>Any questions, requests, or problems can be sent to Brigade Automation at 706-791-7373.</p>
      </td>
  </tr>
</table>

<?

echo com_sitefooter();

?>
