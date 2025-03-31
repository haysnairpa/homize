<?php

namespace App\Helpers;

class HariHelper
{
    public static function formatHari($hariString)
    {
        if (!$hariString) return '';
        
        $hariArray = explode(',', $hariString);
        sort($hariArray);
        
        if (count($hariArray) === 1) {
            return $hariArray[0];
        }
        
        // Check if days are consecutive
        $isConsecutive = true;
        for ($i = 1; $i < count($hariArray); $i++) {
            $currentIndex = array_search($hariArray[$i], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $prevIndex = array_search($hariArray[$i-1], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            if ($currentIndex - $prevIndex !== 1) {
                $isConsecutive = false;
                break;
            }
        }
        
        if ($isConsecutive) {
            return $hariArray[0] . ' - ' . end($hariArray);
        } else {
            $last = array_pop($hariArray);
            return implode(', ', $hariArray) . ' dan ' . $last;
        }
    }
} 