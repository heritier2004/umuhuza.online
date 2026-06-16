<?php

function formatPrice($amount) {
    return 'RWF ' . number_format((float) $amount, 0, '.', ',');
}

function activeRoute($route) {
    return ($_GET['route'] ?? 'home') === $route ? 'active' : '';
}

function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return true;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function listingCoverUrl($item) {
    if (!empty($item['image_path'])) {
        return $item['image_path'];
    }
    if (!empty($item['image_url'])) {
        return $item['image_url'];
    }

    $category = strtolower($item['category_name'] ?? '');
    if (strpos($category, 'real') !== false) {
        return 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=900&q=80';
    }
    if (strpos($category, 'tech') !== false || strpos($category, 'repair') !== false || strpos($category, 'plumb') !== false) {
        return 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=900&q=80';
    }

    return 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=80';
}

function renderCategoryIcon($name) {
    $icons = [
        'home' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-5H9v5H4a1 1 0 0 1-1-1v-8.5z"/></svg>',
        'tv' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="6" width="16" height="12" rx="2"/><path d="M9 18h6"/></svg>',
        'droplet' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3c3 4 6 7 6 10a6 6 0 1 1-12 0c0-3 3-6 6-10z"/></svg>',
        'bolt' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/></svg>',
        'sparkles' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3l1.7 4.3L18 9l-4.3 1.7L12 15l-1.7-4.3L6 9l4.3-1.7L12 3z"/><path d="M19 14l.9 2.1L22 17l-2.1.9L19 20l-.9-2.1L16 17l2.1-.9L19 14z"/></svg>',
        'grid' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/></svg>',
    ];

    return $icons[$name] ?? $icons['grid'];
}
