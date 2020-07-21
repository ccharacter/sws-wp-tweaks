<?php

header('Content-type: application/json');

$extensions="https://docs.google.com/spreadsheets/d/e/2PACX-1vTMtU2xcD85-JGUC7YSyqA8gJSRE0S2jchGwx7pTEBM0Ctbwdtyfy6K0SGWc_3OxX7CRjeNyXYllAtQ/pub?output=csv";

$keywords="https://docs.google.com/spreadsheets/d/e/2PACX-1vTbMPp5ITCS8-jUzN4bECUu5st9BmQ-9mZEXrqQpW3O0tcHKrNbvAk_-0l5ecoqgHV3Wka3uwnFegkG/pub?output=csv"; // LOGIN BANNING



// Function to convert CSV into associative array
function sws_tweaks_csvToArray($file, $delimiter) { 
  if (($handle = fopen($file, 'r')) !== FALSE) { 
    $i = 0; 
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
    fclose($handle); 
  } 
  return $arr; 
} 


?>