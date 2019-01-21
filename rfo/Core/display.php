<?php 

function StartTable() {
    echo '<table><tr>';
}

function EndTable() {
    echo "</tr></table>";
}

function TH($header, $span = '1') {
    echo '<th colspan="' . $span . '">' . $header . '</th>';
}

function TR($data, $span = '1') {
    echo '<tr colspan="' . $span . '">"' . $data . '</tr>';
} 

function NoDataRow($array, $colspan) {
    if($array[0] == 0) {
	echo '<tr><td colspan="' . $colspan . '"><b>No data exists in the table.</b></td></tr>';
	}
}

function DisplayFileName() {
    echo $_SERVER['PHP_SELF'] . '<br/>';
}

?>