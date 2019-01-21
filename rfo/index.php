<?php #$root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    $lifetime = 60 * 60 * 24; //24 hours
    ini_set('session.use_only_cookies', true);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    session_set_cookie_params($lifetime, '/'); //all paths, must be called before session_start()
    session_save_path(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/sessions'); session_start();
    date_default_timezone_set('America/New_York');
    
    #$_SESSION['rootDir'] = "/";
    $_SESSION['rootDir'] = "";
    include_once $_SESSION['rootDir'] . 'core/include.php';
    
    include_once 'view/header.php';  
    
    if($_SESSION['valid_user']) :
        
	if(empty($_SESSION['admin_enabled'])) $_SESSION['admin_enabled'] = false;
	if(empty($_SESSION['debug'])) $_SESSION['debug'] = false;
	if(empty($_SESSION['error_message'])) $_SESSION['error_message'] = '';
	
	if(isset($_POST['guest_mode'])) {
		if($_POST['guest_mode'] == 1) $_SESSION['admin_enabled'] = false;
	}

    

    //display error message
    if($_SESSION['error_message'] != '') { echo '<br/><b id=error>' . $_SESSION['error_message'] . '</b><br/><br/>'; }

    if(isset($_POST['action'])) {
        $action = $_POST['action'];
    } else { $action = 'default'; }
    
    //perform necessary action, sent by forms
    switch($action) {
	case 'SM_add': 
	    if(isset($_POST['pending_add'])) {
		//code for adding a soldier
		if(isset($_POST['SSN'])) {
		    $soldier->SetSSN($_POST['SSN']);

		    if($soldier->IsValidSSN()) {
			$soldier->SetName($_POST['LastName']);
			$soldier->SetRank($_POST['Rank']);
			$soldier->SetClassNumber($_POST['ClassNumber']);
			
			if(isset($_POST['Component'])) { 
			    $soldier->SetComponent($_POST['Component']);

			    echo "<b>" . $soldier->AddToDB() . " Soldier(s) added.</b><br/>";
			}
		    } else { echo "<b>SSN entered was invalid.</b><br/>";
		    }

		    include 'view/home.php';
		}
	    } else {
		include 'view/SM_add.php';
	    } break;
	case 'SM_remove':
		if(isset($_POST['select_all'])) { $select_all = true; }
		if(isset($_POST['select_none'])) { $select_all = false; }
		
	    if(isset($_POST['pending_removal'])) {
			$sol_amount = 0;
			$rfo_amount = 0;

			$removenext = false;
			foreach($_POST as $tpost) {
				if($removenext) {
					$alpha = $db->GetTable('alpha', 'LastName');
					foreach($alpha as $talpha) {
						if($tpost == $talpha['SSN']) {
							$soldier->SetFromDB($talpha['SSN']);
							if($soldier->GetRFO()->GetCompletion()) {
								$rfo_amount += $soldier->GetRFO()->GetCompletion();
							}
							$sol_amount += $soldier->RemoveFromDB();
						}
					}
				}


				if($tpost == 'on') { $removenext = true; } else { $removenext = false; }
			}

			echo "<b>" . $rfo_amount . " RFO(s) removed.</b><br/>";
			echo "<b>" . $sol_amount . " Soldier(s) removed.</b><br/>";

			include 'view/home.php';
	    } else { 
		include 'view/SM_remove.php'; 
	    } break;
	case 'SM_edit':
	    if(isset($_POST['SSN'])) {
		$soldier->SetFromDB($_POST['SSN']);

		if(isset($_POST['pending_update'])) {
		    //update soldier
		    $soldier->SetName($_POST['LastName']);
			$soldier->SetClassNumber($_POST['ClassNumber']);
			$soldier->SetRank($_POST['Rank']);
			
		    $oldcomp = $soldier->GetComponent();
		    $newcomp = $_POST['Component'];
		    if($oldcomp != $newcomp) {
			//if RA is the new component
			if($newcomp == 'RA' && $soldier->GetRFO()->GetCompletion() == "1") {
			    echo "<b>" . $soldier->GetRFO()->RemoveFromDB() . " RFO removed, you will have to complete a new RFO.<br>";
			} else if($oldcomp == 'RA' && $soldier->GetRFO()->GetCompletion() == "1") {
			    echo "<b>" . $soldier->GetRFO()->RemoveFromDB() . " RFO removed, you will have to complete a new RFO.<br>";
			}
		    }

		    //$soldier->ChangeSSN_In_DB($_POST['new_SSN']);
		    $soldier->SetComponent($newcomp);

		    echo "<b>" . $soldier->UpdateDB() . " Soldier(s) edited successfully.</b><br/>";
		    include 'view/home.php';
		} else { //goto SM_edit
		    include 'view/SM_edit.php';
		}
	    } else { echo "<b>Error retreiving Soldier.</b><br/>"; }
	    break;
	case 'SM_import':
	    if(isset($_POST['pending_update'])) {
			//add all SM's from table
			$added = 0; $invalid;
			$total = $_POST['TotalPersonnel'];
			for($i = 1; $i <= $total; $i++) {
				$soldier->SetSSN($_POST['SSN' . $i]);

				if($soldier->IsValidSSN()) {
					$soldier->SetName($_POST['LastName' . $i]);
					$soldier->SetRank($_POST['Rank' . $i]);
					$soldier->SetClassNumber($_POST['ClassNumber']);
					$soldier->SetComponent($_POST['Component' . $i]);

					$added += $soldier->AddToDB();
				} else { $invalid += 1;
				}
			}
			if($invalid) echo "<b>" . $invalid . " SSN(s) entered was invalid.</b><br/>";
			echo "<b>" . $added . " Soldier(s) added.</b><br/>";
			
			include 'view/home.php';
	    } else {
		include 'view/SM_import.php';
		//include 'view/home.php';
	    }
	    break;
	case 'SM_show':
	    //code to view the alpha roster
	    include 'view/SM_show.php';
	    break;
	case 'RFO_export':
			include_once 'core/rfo_export.php';
			
                        ExportRFO($soldier, 'RA');
                        ExportRFO($soldier, 'NGER');
            
			echo '<b>Export Successful!</b><br/><br/>';
                        echo '<a href="Exported\D447-RFO-RA.xls">Open RA RFO</a><br/>';
                        echo '<a href="Exported\D447-RFO-NGER.xls">Open NG/ER RFO</a><br/>';
			
			include 'view/home.php';
		break;
	case 'rfo_list':
	    include 'view/rfo_list.php';
	    break;
	case 'rfo_start':
	    //start with selected ssn
	    $soldier->SetFromDB($_POST['SSN']);

		if(isset($_POST['Password'])) {
			if($_POST['Password'] == $soldier->GetSSN() && $_POST['Agree'] == 'Yes') {
				include 'view/rfo_start.php';
			} else {
				include 'view/rfo_terms.php';
			}
		}
	    break;
	case 'rfo_show':
	    //start with selected ssn
	    $soldier->SetFromDB($_POST['SSN']);

	    include 'view/rfo_show.php';
	    break;
	case 'rfo_terms':
	    //start with selected ssn
	    $soldier->SetFromDB($_POST['SSN']);

	    include 'view/rfo_terms.php';
	    break;
	case 'rfo_show_all':
		include 'view/rfo_show_all.php';
		break;
	case 'rfo_add':
	    //set all variables
	    $soldier->SetFromDB($_POST['SSN']);

	    if($soldier->GetComponent() == 'RA') {
		$leave = $_POST['TakingLeave'];
		$POV = $_POST['POV'];
		$family = $_POST['Family'];
		$POR = $_POST['POR'];
		$travel = 'N/A';
	    } else if($soldier->GetComponent() == 'NG' || $soldier->GetComponent() == 'ER') {
		$leave = 'N/A';
		$POV = 'N/A';
		$family = 'N/A';
		$POR = 'N/A';
		$travel = $_POST['Travel'];
	    } else { echo "<b>Invalid component.</b><br/>"; }

	    $soldier->GetRFO()->Set($_POST['Airborne'], $_POST['HRAP'], $_POST['APFT'], $_POST['SecurityClearance'], $_POST['UCMJ'], $leave, $POV, $family, $POR, $_POST['Profile'], $_POST['DentalCategory'], $_POST['PHA'], $travel);

	    if(!isset($_POST['update_rfo'])) { $num = $soldier->GetRFO()->AddToDB();
	    } else { $num = $soldier->GetRFO()->UpdateDB();
	    }

	    if($num) { 
		$soldier->GetRFO()->SetCompletion(1);
		$soldier->UpdateDB();
		echo "<b>" . $num . " RFO(s) completed successfully.</b><br/>";
	    } else { echo "<b>Soldier already completed an RFO.</b><br/>"; }

	    include 'view/home.php';
	    break;
	case 'rfo_edit':
	    $soldier->SetFromDB($_POST['SSN']);
	    
	    include 'view/rfo_edit.php';
		break;
	case 'CL_add': 
	    if(isset($_POST['pending_cl_add'])) {
		//code for adding a soldier
		if(isset($_POST['ClassNumber'])) {
		    $class->SetClassNumber($_POST['ClassNumber']);

		    if(isset($_POST['GradDate'])) { 
			$class->SetGradDate($_POST['GradDate']);

			echo "<b>" . $class->AddToDB() . " Class(es) added.</b><br/>";
		    }

		    include 'view/home.php';
		}
	    } else {
		include 'view/CL_add.php';
	    } break;
	case 'CL_remove':
	    if(isset($_POST['pending_cl_removal'])) {
		$cl_amount = 0;

		$removenext = false;
		foreach($_POST as $tpost) {
		    if($removenext) {
			$table = $db->GetTable('classes', 'ClassNumber');
			foreach($table as $ttable) {
			    if($tpost == $ttable['ClassNumber']) {
				$class->SetFromDB($ttable['ClassNumber']);
				
				$cl_amount += $class->RemoveFromDB();
			    }
			}
		    }


		    if($tpost == 'on') { $removenext = true; } else { $removenext = false; }
		}

		echo "<b>" . $cl_amount . " Class(es) removed.</b><br/>";

		include 'view/home.php';
	    } else { 
		include 'view/CL_remove.php'; 
	    } break;
	case 'CL_edit':
	    if(isset($_POST['ClassNumber'])) {
		$class->SetFromDB($_POST['ClassNumber']);

		if(isset($_POST['pending_cl_update'])) {
		    //update class
		    $class->SetClassNumber($_POST['ClassNumber']);
		    $class->SetGradDate($_POST['GradDate']);

		    echo "<b>" . $class->UpdateDB() . " Class(es) edited successfully.</b><br/>";
		    include 'view/home.php';
		} else { //goto CL_edit
		    include 'view/CL_edit.php';
		}
	    } else { echo "<b>Error retreiving Class.</b><br/>"; }
	    break;
	case 'CL_show':
	    //code to view the alpha roster
	    include 'view/CL_show.php';
	    break;
	case 'CL_import':
			include 'core/import.php';
			include 'view/home.php';
		break;
	case 'default': 
	    //do default action, load home page
	    include 'view/home.php';
	    break;
    } //end of switch statement
    
    include 'view/footer.php'; 
?>
   
<?php else : ?>
    <b id="error">&nbsp;&nbsp;You do not have permission to view this site.</b><br/>
<?php include '/view/rightbar.php'; endif; ?>