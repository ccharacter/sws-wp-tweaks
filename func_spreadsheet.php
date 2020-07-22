<?php

header('Content-type: application/json');

// Function to convert CSV into associative array
function sws_tweaks_csvToArray($file, $delimiter, $header_row="Y") { 
  if (($handle = fopen($file, 'r')) !== FALSE) { 
    $i = 0; 
	
	if ($header_row=="Y") { 
		while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
			if ($i==0) { 
				for ($j = 0; $j < count($lineArray); $j++) { 
					$colArr[] = $lineArray[$j]; 
				}
			} else {
				  for ($j = 0; $j < count($lineArray); $j++) { 
					$col=$colArr[$j];
					$arr[$i][$col] = $lineArray[$j]; 
				  } 
			}
		  $i++; 
		} 
	} else { 
		while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
		  for ($j = 0; $j < count($lineArray); $j++) { 
			$tmpArr=array();
			$tmpArr[] = $lineArray[$j]; 
		  } 
		  $arr[$i]=$tmpArr;
		  $i++; 
		} 
	}

    fclose($handle); 
  } 
  return $arr; 
} 


?>