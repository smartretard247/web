<?php

include('survey.class.php');

$survey = new Survey;

echo $survey->com_header("New Answer Type");
echo $survey->new_answer_type(@$_REQUEST['sid']);
echo $survey->com_footer();

?>
