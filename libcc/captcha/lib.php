<?php
function str_rand($string_length, $string_type='numeric') { 
        $string_array = array('numeric','alpha','alnum','alnum_cap','alnum_low','alpha_cap','alpha_low'); 
        if(array_search($string_type, $string_array) !== FALSE) { 
            if($string_length < 40) { 
                switch($string_type) { 
                    case 'numeric': 
                        $string_seed = range('1','9'); 
                    break; 
                    case 'alpha': 
                        $string_seed = array_merge(range('A','Z'), range('a','z')); 
                    break; 
                    case 'alpha_cap': 
                        $string_seed = range('A','Z'); 
                    break; 
                    case 'alpha_low': 
                        $string_seed = range('a','z'); 
                    break; 
                    case 'alnum': 
                        $string_seed = array_merge(range('1','9'), array_merge(range('A','Z'), range('a','z'))); 
                    break; 
                    case 'alnum_cap': 
                        $string_seed = array_merge(range('1','9'), range('A','Z')); 
                    break; 
                    case 'alnum_low': 
                        $string_seed = array_merge(range('1','9'), range('a','z')); 
                    break; 
                } 
                $string_random = ''; 
                for($i=0;$i<$string_length;$i++) { 
                    $string_key = array_rand($string_seed, 1); 
                    $string_random .= $string_seed[$string_key]; 
                } 
                return $string_random; 
            } 
            else { 
                return 'Invalid string length'; 
            } 
        } 
        else { 
            return 'Invalid string type'; 
        } 
    } 
?>