<?php

class RequestController {
    public function submit($pdo) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                flash('error', 'Security token invalid. Please try again.');
                header('Location: ?route=home');
                exit;
            }

            $requestId = RequestModel::create($pdo, [
                'name' => sanitize($_POST['name'] ?? ''),
                'phone' => sanitize($_POST['phone'] ?? ''),
                'whatsapp' => sanitize($_POST['whatsapp'] ?? ''),
                'province' => sanitize($_POST['province'] ?? ''),
                'district' => sanitize($_POST['district'] ?? ''),
                'sector' => sanitize($_POST['sector'] ?? ''),
                'budget' => sanitize($_POST['budget'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'type' => sanitize($_POST['type'] ?? 'service'),
            ]);

            $matched = 0;
            if ($requestId && $pdo) {
                $province = sanitize($_POST['province'] ?? ($_SESSION['current_province'] ?? ''));
                $district = sanitize($_POST['district'] ?? ($_SESSION['current_district'] ?? ''));
                $sector = sanitize($_POST['sector'] ?? ($_SESSION['current_sector'] ?? ''));
                $cell = sanitize($_POST['cell'] ?? ($_SESSION['current_cell'] ?? ''));
        $village = sanitize($_POST['village'] ?? ($_SESSION['current_village'] ?? ''));
                $reqType = sanitize($_POST['type'] ?? 'service');
                $reqDesc = sanitize($_POST['description'] ?? '');

                // Helper to classify request
                $text = strtolower($reqType . ' ' . $reqDesc);
                $targetType = 'service_provider';
                $targetCategory = 'general';

                if (strpos($text, 'real estate') !== false || strpos($text, 'house') !== false || strpos($text, 'apartment') !== false || strpos($text, 'property') !== false || strpos($text, 'rent') !== false || strpos($text, 'land') !== false || strpos($text, 'plot') !== false || strpos($text, 'room') !== false || strpos($text, 'sell') !== false || strtolower($reqType) === 'real estate') {
                    $targetType = 'agent';
                    $targetCategory = 'all';
                } else {
                    if (strpos($text, 'electric') !== false || strpos($text, 'power') !== false || strpos($text, 'wiring') !== false || strpos($text, 'light') !== false) {
                        $targetCategory = 'electrician';
                    } elseif (strpos($text, 'plumb') !== false || strpos($text, 'leak') !== false || strpos($text, 'pipe') !== false || strpos($text, 'water') !== false || strpos($text, 'clog') !== false || strpos($text, 'toilet') !== false || strpos($text, 'sink') !== false) {
                        $targetCategory = 'plumber';
                    } elseif (strpos($text, 'car ') !== false || strpos($text, 'motor') !== false || strpos($text, 'engine') !== false || strpos($text, 'vehicle') !== false || strpos($text, 'mechanic') !== false || strpos($text, 'brake') !== false) {
                        $targetCategory = 'mechanic';
                    } elseif (strpos($text, 'paint') !== false || strpos($text, 'color') !== false || strpos($text, 'wall') !== false) {
                        $targetCategory = 'painter';
                    } elseif (strpos($text, 'tech') !== false || strpos($text, 'computer') !== false || strpos($text, 'tv') !== false || strpos($text, 'repair') !== false || strpos($text, 'electronic') !== false || strpos($text, 'fridge') !== false || strpos($text, 'appliance') !== false) {
                        $targetCategory = 'technician';
                    } elseif (strpos($text, 'weld') !== false || strpos($text, 'metal') !== false || strpos($text, 'iron') !== false || strpos($text, 'gate') !== false) {
                        $targetCategory = 'welder';
                    } elseif (strpos($text, 'clean') !== false || strpos($text, 'wash') !== false || strpos($text, 'laundry') !== false || strpos($text, 'housekeep') !== false) {
                        $targetCategory = 'cleaner';
                    } elseif (strpos($text, 'move') !== false || strpos($text, 'transport') !== false || strpos($text, 'pack') !== false || strpos($text, 'shift') !== false || strpos($text, 'logistic') !== false || strpos($text, 'truck') !== false) {
                        $targetCategory = 'moving';
                    } elseif (strpos($text, 'guard') !== false || strpos($text, 'security') !== false || strpos($text, 'protect') !== false || strpos($text, 'cctv') !== false || strpos($text, 'camera') !== false) {
                        $targetCategory = 'security';
                    } elseif (strpos($text, 'it ') !== false || strpos($text, 'network') !== false || strpos($text, 'software') !== false || strpos($text, 'wifi') !== false || strpos($text, 'router') !== false) {
                        $targetCategory = 'it_support';
                    } elseif (strpos($text, 'freelance') !== false || strpos($text, 'design') !== false || strpos($text, 'write') !== false || strpos($text, 'consult') !== false) {
                        $targetCategory = 'freelancer';
                    } else {
                        $targetCategory = 'all';
                    }
                }

                // Query matching providers
                $stmt = $pdo->prepare("SELECT u.id, u.full_name, u.province, u.district, u.sector, u.cell, u.account_type, u.service_category,
                       COALESCE(up.plan_id, 1) AS plan_id,
                       COALESCE(p.name, 'Free') AS plan_name,
                       COALESCE(p.ranking_priority, 1) AS priority_score,
                       COALESCE((SELECT AVG(rating) FROM listings WHERE user_id = u.id), 4.5) AS rating,
                       GROUP_CONCAT(sa.area SEPARATOR ';') AS service_areas
                FROM users u
                LEFT JOIN user_plans up ON up.user_id = u.id AND up.status = 'active'
                LEFT JOIN plans p ON p.id = up.plan_id
                LEFT JOIN service_areas sa ON sa.user_id = u.id
                WHERE u.role = 'provider' AND u.status = 'active'
                  AND u.account_type = :target_type
                  AND (:target_category = 'all' OR u.service_category = :target_category OR u.account_type = 'agent')
                GROUP BY u.id");

                $stmt->execute([
                    'target_type' => $targetType,
                    'target_category' => $targetCategory,
                ]);
                $allProviders = $stmt->fetchAll();

                $matchedProviders = [];
                $reqProvince = strtolower(trim($province));
                $reqDistrict = strtolower(trim($district));
                $reqSector = strtolower(trim($sector));
                $reqCell = strtolower(trim($cell));

                foreach ($allProviders as $provider) {
                    $saList = !empty($provider['service_areas']) ? array_map('trim', explode(';', strtolower($provider['service_areas']))) : [];
                    $pProvince = strtolower(trim($provider['province'] ?? ''));
                    $pDistrict = strtolower(trim($provider['district'] ?? ''));
                    $pSector = strtolower(trim($provider['sector'] ?? ''));
                    $pCell = strtolower(trim($provider['cell'] ?? ''));

                    $matchLevel = null;

                    // Priority 0: Exact Cell or Village match or service area match
                    if (
                        ($reqCell !== '' && $pCell === $reqCell) ||
                        ($village !== '' && ($provider['village'] ?? '') === $village) ||
                        in_array($reqCell, $saList) ||
                        in_array($village, $saList)
                    ) {
                        $matchLevel = 0;
                    }
                    // Priority 1: Same Sector
                    elseif ($reqSector !== '' && $pSector === $reqSector) {
                        $matchLevel = 1;
                    }
                    // Priority 2: Same District
                    elseif ($reqDistrict !== '' && $pDistrict === $reqDistrict) {
                        $matchLevel = 2;
                    }
                    // Priority 3: Same Province
                    elseif ($reqProvince !== '' && $pProvince === $reqProvince) {
                        $matchLevel = 3;
                    }
                    // Priority 4: Nearby Province (any provider with a province)
                    elseif ($pProvince !== '') {
                        $matchLevel = 4;
                    }

                    if ($matchLevel !== null) {
                        $provider['match_level'] = $matchLevel;
                        $matchedProviders[] = $provider;
                    }
                }

                // Sort matching providers
                usort($matchedProviders, function($a, $b) {
                    // 1. Subscription priority (higher is better)
                    if ($a['priority_score'] != $b['priority_score']) {
                        return $b['priority_score'] <=> $a['priority_score'];
                    }
                    // 2. Match level (lower is better)
                    if ($a['match_level'] != $b['match_level']) {
                        return $a['match_level'] <=> $b['match_level'];
                    }
                    // 3. Provider rating (higher is better)
                    return $b['rating'] <=> $a['rating'];
                });

                // Deliver / schedule matches
                foreach ($matchedProviders as $provider) {
                    $priority = (int)$provider['priority_score'];
                    $providerId = (int)$provider['id'];
                    $status = 'delivered';
                    $deliveredAt = date('Y-m-d H:i:s');

                    // Free plan throttling
                    if ($priority === 1) { // Free plan assumed priority_score = 1
                        $countStmt = $pdo->prepare('SELECT COUNT(*) AS total FROM request_matches WHERE provider_id = ? AND created_at >= DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00")');
                        $countStmt->execute([$providerId]);
                        $monthlyCount = (int)$countStmt->fetch()['total'];

                        if ($monthlyCount < 5) {
                            $status = 'delivered';
                            $deliveredAt = date('Y-m-d H:i:s');
                        } elseif ($monthlyCount >= 5 && $monthlyCount < 10) {
                            $status = 'pending';
                            $deliveredAt = date('Y-m-d H:i:s', time() + 45 * 60);
                        } else {
                            $status = 'blocked';
                            $deliveredAt = null;
                        }
                    }

                    $matchStmt = $pdo->prepare('INSERT INTO request_matches (request_id, provider_id, match_level, priority_score, status, delivered_at) VALUES (?, ?, ?, ?, ?, ?)');
                    $matchStmt->execute([
                        $requestId,
                        $providerId,
                        $provider['match_level'],
                        $priority,
                        $status,
                        $deliveredAt,
                    ]);

                    if ($status === 'delivered') {
                        NotificationModel::create($pdo, $providerId, 'New request in ' . trim(($district ?: $province) . ' / ' . ($sector ?: '')) . ': ' . sanitize($reqType));
                        $matched++;
                    }
                }
            }

            flash('success', $matched > 0 ? 'Request submitted. Matches were notified.' : 'Request submitted. Providers will be matched shortly.');
            header('Location: ?route=home');
            exit;
        }
    }
}
