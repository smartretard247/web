<html>
<head><Title>Test</Title>
<Script type="text/javascript">
  
  var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="; 
  
  function encode64(input) { 
     input = escape(input); 
     var output = ""; 
     var chr1, chr2, chr3 = ""; 
     var enc1, enc2, enc3, enc4 = ""; 
     var i = 0; 
  
     do { 
        chr1 = input.charCodeAt(i++); 
        chr2 = input.charCodeAt(i++); 
        chr3 = input.charCodeAt(i++); 
  
        enc1 = chr1 >> 2; 
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4); 
        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6); 
        enc4 = chr3 & 63; 
  
        if (isNaN(chr2)) { 
           enc3 = enc4 = 64; 
        } else if (isNaN(chr3)) { 
           enc4 = 64; 
        } 
  
        output = output + 
           keyStr.charAt(enc1) + 
           keyStr.charAt(enc2) + 
           keyStr.charAt(enc3) + 
           keyStr.charAt(enc4); 
        chr1 = chr2 = chr3 = ""; 
        enc1 = enc2 = enc3 = enc4 = ""; 
     } while (i < input.length); 
  
     return output; 
  } 
  
  function decode64(input) { 
     var output = ""; 
     var chr1, chr2, chr3 = ""; 
     var enc1, enc2, enc3, enc4 = ""; 
     var i = 0; 
  
     // remove all characters that are not A-Z, a-z, 0-9, +, /, or = 
     var base64test = /[^A-Za-z0-9\+\/\=]/g; 
     if (base64test.exec(input)) { 
        alert("There were invalid base64 characters in the input text.\n" + 
              "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" + 
              "Expect errors in decoding."); 
     } 
     input = input.replace(/[^A-Za-z0-9\+\/\=]/g, ""); 
  
     do { 
        enc1 = keyStr.indexOf(input.charAt(i++)); 
        enc2 = keyStr.indexOf(input.charAt(i++)); 
        enc3 = keyStr.indexOf(input.charAt(i++)); 
        enc4 = keyStr.indexOf(input.charAt(i++)); 
  
        chr1 = (enc1 << 2) | (enc2 >> 4); 
        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2); 
        chr3 = ((enc3 & 3) << 6) | enc4; 
  
        output = output + String.fromCharCode(chr1); 
  
        if (enc3 != 64) { 
           output = output + String.fromCharCode(chr2); 
        } 
        if (enc4 != 64) { 
           output = output + String.fromCharCode(chr3); 
        } 
  
        chr1 = chr2 = chr3 = ""; 
        enc1 = enc2 = enc3 = enc4 = ""; 
  
     } while (i < input.length); 
  
     return unescape(output); 
  } 



function send() {
  document.login.psw.value = encode64(document.login.psw.value);
  alert(document.login.psw.value);
  document.login.submit();
 }
</script>
</head>
<body>

<?
//=============================   encryption test   ==========================================




//    === FUNCTIONS AREA ================
function encrypt($string, $key) {
  return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key)))); }

function decrypt($string, $key) {
  return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0"); }


function checkPassword($value){
  return preg_match("/.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.+[0-9])(?=.*[!@#$\%^&*]).*/",$value) ? $value : false; }
 //==============================================================================================
 //  /.* - indicate pattern start with any alphanumeric zero or more (. aphanumeric) (* zero or more)
 //  ?=  - the next text must be like this
 //  (?=.{8,}) - at least 8 characters long (() - group)
 //  (?=.*[a-z]) - must have zero or more lower case
 //  (?=.*[A-Z]) - must have zero or more upper case
 //  (?=.*[0-9]) - must have zero or more numbers
 //  (?=.*[!@#$\%^&*]) - must have zero or more of these special characters
 //  .*/ - indicate pattern ends with any alphanumeric zero or more
 //===============================================================================================


/**  * MySQL "OLD_PASSWORD()" AKA MySQL323 HASH FUNCTION  
     * This is the password hashing function used in MySQL prior to version 4.1.1  
     * By Rev. Dustin Fineout 10/9/2009 9:12:16 AM 
**/ 

function mysql_old_password($input, $hex = true) {
   $nr = 1345345333; $add = 7; $nr2 = 0x12345671; $tmp = null;
   $inlen = strlen($input);
   for ($i = 0; $i < $inlen; $i++) {
     $byte = substr($input, $i, 1);
     if ($byte == ' ' || $byte == "\t") continue;
     $tmp = ord($byte);
     $nr ^= ((($nr & 63) + $add) * $tmp) + (($nr << 8) & 0xFFFFFFFF);
     $nr2 += (($nr2 << 8) & 0xFFFFFFFF) ^ $nr;
     $add += $tmp;
   }
   $out_a = $nr & ((1 << 31) - 1);
   $out_b = $nr2 & ((1 << 31) - 1);
   $output = sprintf("%08x%08x", $out_a, $out_b);
   if ($hex) return $output;
   return hex_hash_to_bin($output);
 }//END function mysql_old_password_hash  


//    === FUNCTIONS AREA ================

  $input['name']    = "jose";
  $input['age']     = 45;
  $input['address'] = "765 C Walnut CT";
  $input['raw_pu']  = 50;
/*  
  foreach($input as $key => $value) 
    echo "The $key is: $value<br>\n";
*/	
//  $test = "The name is {$input['name']} and he is {$input['age']} years old...<br>\n";
//  echo $test;

  echo "The name is {$input['name']} and he is {$input['age']} years old...<br>\n";
  
  $age_query = "select a.category, m.gender, m.rank,{$input['age']} 
                from age_categories a, main m
                where ({$input['age']} between a.min_age and a.max_age) and m.id = {$input['name']}<br/>\n<br/>\n";
  echo $age_query;
  
  $gender = "male";
  $age_category = (intval(($input['age'] -2) / 5) - 2);
    
  $pu_query = "select age$age_category 
               from {$gender}_pushups 
			   where repetitions <= {$input['raw_pu']} order by age$age_category desc limit 1";
			   
  echo $pu_query;

echo "<HR>\n";
echo "ENCRYPTION DEMO<br/>\n";
$encrypted = encrypt("The Quick Brown Fox Jumps Over T", "19686");
echo "Encrypted : $encrypted <br/>\n";
echo "length of encrypted string :" .strlen($encrypted) ."<br/>\n";
$decrypted = decrypt($encrypted, "19686");
echo "Decrypted : $decrypted<br/>\n";
echo "length of string :" .strlen($decrypted) ."<br/>\n";
echo "Ratio is 32:44 chars...<br/>\n";
echo "<HR>\n";
//demo to use binary-decimal structure for permissions
$format = '(%1$2d = %1$08b) = (%2$2d = %2$08b)'
        . ' %3$s (%4$2d = %4$08b)' . "<br/>\n";

$values = array(0, 1, 2, 4, 8, 16);
$test = 5;

foreach ($values as $value) {
    $result = $value & $test;
    printf($format, $result, $value, '&', $test);
}
echo "<HR>\n";
$lp = "0000010000000001000000000010001";
$hp = "0000000000000000000000000000010";
$permission = 26;

$allowed = ($permission<=32) ? (bindec($lp)&pow(2,$permission-1)) : (bindec($hp)&pow(2,($permission-1)-32));
echo "user is " . (($allowed) ? "ALLOWED" : "NOT ALLOWED") . " to access this area with permission # $permission";

echo "<HR>\n";
$psw = "11aa123@Aa1Aa";
echo "$psw ";
if (checkPassword($psw)) echo "Good Password"; else echo "Bad Password";

echo "<HR>\n";
    $date = date("d") . strtoupper(date("M")) . date("Y");
    echo $date;
	
echo "<HR>\n";

$SESSION['user_id'] = "12345";

	$query = "SELECT b.battalion_id, b.battalion, c.company_id, c.company 
	          FROM battalion b, company c, user_permissions up 
			  WHERE up.battalion_id = b.battalion_id and up.company_id = c.company_id and $p and up.user_id = '{$SESSION['user_id']}' 
			  GROUP BY b.battalion, c.company order by b.battalion, c.company" ;

echo $query;

echo "<HR>\n";
echo mysql_old_password("password") . "<br/>\n";
echo "5d2e19393cc5ef67";
?>
<form name = "login" method="post" action="decrypt.php">
  <input type="text" name="psw">
  <input type="button" value="submit" onClick="send();">
</form>
</body>
</html>