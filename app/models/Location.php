<?php

class LocationModel {
    public static function provinces($pdo) {
        $stmt = $pdo->query('SELECT * FROM provinces ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public static function districts($pdo, $provinceId) {
        $stmt = $pdo->prepare('SELECT * FROM districts WHERE province_id = ? ORDER BY name ASC');
        $stmt->execute([$provinceId]);
        return $stmt->fetchAll();
    }

    public static function sectors($pdo, $districtId) {
        $stmt = $pdo->prepare('SELECT * FROM sectors WHERE district_id = ? ORDER BY name ASC');
        $stmt->execute([$districtId]);
        return $stmt->fetchAll();
    }

    public static function cells($pdo, $sectorId) {
        $stmt = $pdo->prepare('SELECT * FROM cells WHERE sector_id = ? ORDER BY name ASC');
        $stmt->execute([$sectorId]);
        return $stmt->fetchAll();
    }
}
