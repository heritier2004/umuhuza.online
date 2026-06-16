<?php

function rankListings($listings) {
    $currentProvince = $_SESSION['current_province'] ?? '';
    $currentDistrict = $_SESSION['current_district'] ?? '';
    $currentSector = $_SESSION['current_sector'] ?? '';
    $currentCell = $_SESSION['current_cell'] ?? '';

    usort($listings, function ($a, $b) use ($currentProvince, $currentDistrict, $currentSector, $currentCell) {
        $aLevel = 5;
        $bLevel = 5;
        if (!empty($currentCell) && ($a['cell'] ?? '') === $currentCell) $aLevel = 1;
        if (!empty($currentCell) && ($b['cell'] ?? '') === $currentCell) $bLevel = 1;
        if ($aLevel === $bLevel && !empty($currentSector) && ($a['sector'] ?? '') === $currentSector) $aLevel = 2;
        if ($aLevel === $bLevel && !empty($currentSector) && ($b['sector'] ?? '') === $currentSector) $bLevel = 2;
        if ($aLevel === $bLevel && !empty($currentDistrict) && ($a['district'] ?? '') === $currentDistrict) $aLevel = 3;
        if ($aLevel === $bLevel && !empty($currentDistrict) && ($b['district'] ?? '') === $currentDistrict) $bLevel = 3;
        if ($aLevel === $bLevel && !empty($currentProvince) && ($a['province'] ?? '') === $currentProvince) $aLevel = 4;
        if ($aLevel === $bLevel && !empty($currentProvince) && ($b['province'] ?? '') === $currentProvince) $bLevel = 4;

        if ($aLevel !== $bLevel) return $aLevel <=> $bLevel;

        $planOrder = ['Super' => 3, 'Premium' => 2, 'Free' => 1];
        $aPriority = $planOrder[$a['plan_name']] ?? 0;
        $bPriority = $planOrder[$b['plan_name']] ?? 0;
        if ($aPriority !== $bPriority) return $bPriority <=> $aPriority;
        return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
    });
    return $listings;
}
