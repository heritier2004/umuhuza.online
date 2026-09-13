<?php

// Upload size constraints (Min 5 KB, Max 3 MB to protect system speed and bandwidth)
define('UPLOAD_MIN_SIZE_BYTES', 5 * 1024);          // 5 KB
define('UPLOAD_MAX_SIZE_BYTES', 3 * 1024 * 1024);    // 3 MB

/**
 * Validates and handles file uploads with strict min/max size limits and MIME checks.
 *
 * @param array|null $file $_FILES array element
 * @param string $folder Target upload folder
 * @param int $minSize Minimum allowed size in bytes (default 5 KB)
 * @param int $maxSize Maximum allowed size in bytes (default 3 MB)
 * @return array Array with ['success' => bool, 'path' => string|null, 'error' => string|null]
 */
function handleUploadDetailed($file, $folder = 'public/uploads', $minSize = UPLOAD_MIN_SIZE_BYTES, $maxSize = UPLOAD_MAX_SIZE_BYTES) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'path' => null, 'error' => 'No file selected.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'path' => null, 'error' => 'Upload failed. Error code: ' . $file['error']];
    }

    $fileSize = (int) ($file['size'] ?? 0);

    // Validate Minimum Size (prevent corrupt / 0-byte uploads)
    if ($fileSize < $minSize) {
        $minKb = round($minSize / 1024);
        return [
            'success' => false,
            'path' => null,
            'error' => "File is too small (" . round($fileSize / 1024, 1) . " KB). Minimum allowed size is {$minKb} KB to prevent corrupted files."
        ];
    }

    // Validate Maximum Size (protect server speed & storage)
    if ($fileSize > $maxSize) {
        $maxMb = round($maxSize / (1024 * 1024), 1);
        return [
            'success' => false,
            'path' => null,
            'error' => "File exceeds maximum size (" . round($fileSize / (1024 * 1024), 2) . " MB). Maximum allowed size is {$maxMb} MB to preserve system speed."
        ];
    }

    // Validate File Extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed_extensions)) {
        return [
            'success' => false,
            'path' => null,
            'error' => 'Invalid file type. Only JPG, PNG, WEBP, and GIF images are supported.'
        ];
    }

    // Validate MIME Type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mime = null;
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo && !empty($file['tmp_name'])) {
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
        }
    }
    if (!$mime) {
        $mime = $file['type'] ?? '';
    }

    if ($mime && !in_array($mime, $allowed_types)) {
        return [
            'success' => false,
            'path' => null,
            'error' => 'Invalid image content. Only JPEG, PNG, WEBP, and GIF image files are accepted.'
        ];
    }

    // Generate clean unique filename
    $name = uniqid('img_') . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($file['name']));
    $targetDir = __DIR__ . '/../../' . trim($folder, '/');
    $targetFile = $targetDir . '/' . $name;
    
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => true, 'path' => trim($folder, '/') . '/' . $name, 'error' => null];
    }

    return ['success' => false, 'path' => null, 'error' => 'Server failed to save uploaded image.'];
}

/**
 * Backward compatible wrapper for handleUpload
 */
function handleUpload($file, $folder = 'public/uploads') {
    $result = handleUploadDetailed($file, $folder);
    return $result['success'] ? $result['path'] : null;
}
