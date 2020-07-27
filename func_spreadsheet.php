<?php

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
		while (($row = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
			$arr[]=$row[0];
		} 
	}

    fclose($handle); 
  } 
  return $arr; 
} 


?>