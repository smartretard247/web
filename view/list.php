<?php $items = $db->GetTable('accounts', 'ID');
    StartTable();
    TH('Account');
    TH('Type');
    TH('Edit');
    echo"</tr>" ;

if($items) { foreach ($items as $titem) : ?>
<?php echo "<tr><td>" . $titem['ID'] . "</td>"; ?>
<?php echo "<td>" . $titem['AccountType'] . "</td>"; ?>
<td>
    <form method="post">
        <input type="hidden" name="action" value="AC_edit"/>
        <input type="hidden" name="ID" value="<?php echo $titem['ID']; ?>"/>
        <input type="hidden" name="ThePassword" value="<?php echo $titem['ThePassword']; ?>"/>
        <input type="hidden" name="AccountType" value="<?php echo $titem['AccountType']; ?>"/>
        <input type="submit" value="Edit"/>
    </form>
</td>
<?php endforeach; } EndTable(); ?>
<br/>