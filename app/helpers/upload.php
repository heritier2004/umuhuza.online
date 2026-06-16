<?php

function handleUpload($file, $folder = 'public/uploads') {
    if (!isset($file['tmp_name']) || !$file['tmp_name']) {
        return null;
    }
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        return null;
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return null;
    }
    
    $name = uniqid('img_') . '_' . basename($file['name']);
    $target = __DIR__ . '/../../' . $folder . '/' . $name;
    if (!is_dir(dirname($target))) {
        mkdir(dirname($target), 0755, true);
    }
    move_uploaded_file($file['tmp_name'], $target);
    return 'public/uploads/' . $name;
}
