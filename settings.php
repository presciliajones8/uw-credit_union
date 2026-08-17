<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/transfer_service.php';
require_auth();

$conn = arffib_db();
$user_id = current_user_id();

$user = [
    'id' => 0,
    'fullName' => 'Guest Customer',
    'balance' => '0.00',
    'idDocument' => 'images/arffib.png',
    'profileImage' => '',
    'email' => '',
    'accountType' => 'Savings',
    'branch' => 'Main Branch',
    'phone' => '',
    'dob' => '',
    'address' => ''
];

if ($user_id !== null) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    if (isset($stmt)) {
        $stmt->close();
    }
}

// Helper function to get profile image path
function get_profile_image($user) {
    if (!empty($user['profileImage']) && $user['profileImage'] !== 'images/arffib.png') {
        return $user['profileImage'];
    }
    return $user['idDocument'] ?? 'images/arffib.png';
}

// Handle profile image upload
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    if (!require_valid_transfer_csrf()) {
        $error = 'Your session could not be verified. Refresh the page and try again.';
    } else {
        $photo = $_FILES['photo'];
        
        // Validate file upload
        if ($photo['error'] !== UPLOAD_ERR_OK) {
            $error = 'File upload failed. Please try again.';
        } elseif ($photo['size'] > 2 * 1024 * 1024) { // 2MB limit
            $error = 'File size exceeds 2MB limit.';
        } else {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $photo['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $error = 'Invalid file type. Only JPEG, PNG, and WEBP are allowed.';
            } else {
                // Validate image content
                $imageInfo = getimagesize($photo['tmp_name']);
                if ($imageInfo === false) {
                    $error = 'Invalid image file.';
                } else {
                    // Generate secure filename
                    $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                    $newFilename = 'profile_' . $user_id . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                    $uploadDir = __DIR__ . '/uploads/profile/';
                    
                    // Create upload directory if it doesn't exist
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $uploadPath = $uploadDir . $newFilename;
                    
                    // Move uploaded file
                    if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                        // Update database
                        $profileImagePath = 'uploads/profile/' . $newFilename;
                        
                        // Get old profile image for cleanup
                        $oldProfileImage = $user['profileImage'];
                        
                        $updateStmt = $conn->prepare("UPDATE users SET profileImage = ? WHERE id = ?");
                        $updateStmt->bind_param("si", $profileImagePath, $user_id);
                        
                        if ($updateStmt->execute()) {
                            $success = 'Profile picture updated successfully.';
                            $user['profileImage'] = $profileImagePath;
                            
                            // Delete old image if it exists and is not the default
                            if ($oldProfileImage && $oldProfileImage !== 'images/arffib.png' && file_exists(__DIR__ . '/' . $oldProfileImage)) {
                                unlink(__DIR__ . '/' . $oldProfileImage);
                            }
                        } else {
                            $error = 'Failed to update profile picture in database.';
                            // Clean up uploaded file
                            unlink($uploadPath);
                        }
                        
                        $updateStmt->close();
                    } else {
                        $error = 'Failed to save uploaded file.';
                    }
                }
            }
        }
    }
}

// Set display profile image after potential upload
$displayProfileImage = get_profile_image($user);

// Handle transaction PIN change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pin']) && isset($_POST['current_password'])) {
    if (!require_valid_transfer_csrf()) {
        $error = 'Your session could not be verified. Refresh the page and try again.';
    } else {
        $newPin = trim($_POST['pin']);
        $currentPassword = $_POST['current_password'];
        
        // Validate PIN format (assuming 6-digit PIN)
        if (!preg_match('/^\d{6}$/', $newPin)) {
            $error = 'Transaction PIN must be exactly 6 digits.';
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
            $stmt->close();
            
            if ($userData && password_verify($currentPassword, $userData['password'])) {
                // Hash new PIN
                $hashedPin = password_hash($newPin, PASSWORD_DEFAULT);
                
                // Update access_code field with new PIN
                $updateStmt = $conn->prepare("UPDATE users SET access_code = ? WHERE id = ?");
                $updateStmt->bind_param("si", $hashedPin, $user_id);
                
                if ($updateStmt->execute()) {
                    $success = 'Transaction PIN updated successfully.';
                } else {
                    $error = 'Failed to update transaction PIN.';
                }
                
                $updateStmt->close();
            } else {
                $error = 'Current password is incorrect.';
            }
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="ABlWjPuCVkEUw9HryA5Ucz0YGMVMlntSND6WRpuH">
    <title>UW CREDIT UNION | Profile</title>
    <meta name="description" content="Swift and Secure Money Transfer to any UK bank account will become a breeze with UW CREDIT UNION." />
    <link rel="shortcut icon" href="images/arffib.png" />
    <link rel="preload" href="path/to/GraphikRegular.otf" as="font" type="font/otf" crossorigin="anonymous">



    <!-- Initial theme colors setup (before anything else loads) -->
    <script>
        // Set CSS theme variables - these match our Tailwind theme
        document.documentElement.style.setProperty('--primary-color', '#0ea5e9');
        document.documentElement.style.setProperty('--primary-color-dark', '#0369a1');
        document.documentElement.style.setProperty('--primary-color-light', '#38bdf8');
        document.documentElement.style.setProperty('--primary-color-lightest', '#38bdf8');
        document.documentElement.style.setProperty('--secondary-color', '#14b8a6');
        document.documentElement.style.setProperty('--secondary-color-dark', '#0f766e');
        document.documentElement.style.setProperty('--secondary-color-light', '#5eead4');
        document.documentElement.style.setProperty('--accent-color', '#ec4899');
        document.documentElement.style.setProperty('--text-color', '#111827');
        document.documentElement.style.setProperty('--bg-color', '#f9fafb');
        document.documentElement.style.setProperty('--sidebar-bg-color', '#1e293b');
        document.documentElement.style.setProperty('--sidebar-text-color', '#ffffff');
        document.documentElement.style.setProperty('--card-bg-color', '#ffffff');
    </script>

    <!-- Tailwind CSS with custom color variables -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#38bdf8',
                            100: '#38bdf8',
                            200: '#38bdf8',
                            300: '#38bdf8',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0ea5e9',
                            700: '#0369a1',
                            800: '#0369a1',
                            900: '#0369a1',
                        },
                        secondary: {
                            50: '#5eead4',
                            100: '#5eead4',
                            200: '#5eead4',
                            300: '#5eead4',
                            400: '#5eead4',
                            500: '#14b8a6',
                            600: '#14b8a6',
                            700: '#0f766e',
                            800: '#0f766e',
                            900: '#0f766e',
                        },
                        accent: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f9a8d4',
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                            800: '#9d174d',
                            900: '#831843',
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>

    
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>

    <!-- Custom Fonts -->
  

    <!-- Modern Loading Animation -->
    <style>
        .page-loading {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: all .4s .2s ease-in-out;
            background-color: #ffffff;
            visibility: hidden;
            z-index: 9999;
        }
        .page-loading.active {
            opacity: 1;
            visibility: visible;
        }
        .page-loading-inner {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            text-align: center;
            transform: translateY(-50%);
            transition: opacity .2s ease-in-out;
            opacity: 0;
        }
        .page-loading.active>.page-loading-inner {
            opacity: 1;
        }

        .loading-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .loading-animation {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            position: relative;
        }

        .loading-animation .circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid transparent;
            mix-blend-mode: overlay;
            animation: rotateCircle 1.5s linear infinite;
        }

        .loading-animation .circle:nth-child(1) {
            border-top-color: var(--primary-color);
            animation-delay: 0s;
        }

        .loading-animation .circle:nth-child(2) {
            border-right-color: var(--primary-color-light);
            animation-delay: 0.2s;
        }

        .loading-animation .circle:nth-child(3) {
            border-bottom-color: var(--secondary-color);
            animation-delay: 0.4s;
        }

        .loading-animation .circle:nth-child(4) {
            border-left-color: var(--primary-color-lightest);
            animation-delay: 0.6s;
        }

        .loading-animation .core {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color-light), var(--primary-color-dark));
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            animation: pulse 1s ease-in-out infinite alternate;
        }

        .page-loading .text {
            color: var(--primary-color);
            font-weight: 500;
            letter-spacing: 0.05em;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            background: linear-gradient(90deg, var(--primary-color-dark), var(--primary-color-light), var(--primary-color-dark));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient 2s linear infinite;
        }

        @keyframes  rotateCircle {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes  pulse {
            from {
                transform: scale(0.8);
                opacity: 0.8;
            }
            to {
                transform: scale(1.2);
                opacity: 1;
            }
        }

        @keyframes  gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <!-- Web Application Manifest -->
<link rel="manifest" href="images/arffib.png">
<!-- Chrome for Android theme color -->
<meta name="theme-color" content="#000000">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="PWA">
<link rel="icon" sizes="512x512" href="/images/icons/icon-512x512.png">

<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="PWA">
<link rel="apple-touch-icon" href="/images/icons/icon-512x512.png">


<link href="/images/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-1242x2208.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-1668x2388.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="/images/icons/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

<!-- Tile for Win8 -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/images/icons/icon-512x512.png">

<script type="text/javascript">
    // Initialize the service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/serviceworker.js', {
            scope: '.'
        }).then(function (registration) {
            // Registration was successful
            console.log('Laravel PWA: ServiceWorker registration successful with scope: ', registration.scope);
        }, function (err) {
            // registration failed :(
            console.log('Laravel PWA: ServiceWorker registration failed: ', err);
        });
    }
</script></head>

<body class="bg-gray-50">
    <!-- Modern Page Loader -->
    <!-- <div class="page-loading active">
        <div class="page-loading-inner">
            <div class="loading-container">
                <div class="loading-animation">
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="core"></div>
                </div>
                <div class="text">UW CREDIT UNION</div>
            </div>
        </div>
    </div> -->

    <!-- Main Layout -->
 <div class="flex h-screen overflow-hidden" style="background-color: ;"
        x-data="{sidebarOpen: false, mobileMenuOpen: false, userDropdownOpen: false, notificationsOpen: false}">
        <!-- Sidebar - Desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col bg-white h-full border-r border-gray-200 shadow-sm">
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                <!-- Logo -->
               

                <!-- User Info Card - Desktop Sidebar -->
                <div class="px-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 mr-3">
                                <img src="<?php echo htmlspecialchars($displayProfileImage); ?>"
                                    alt="Teting"
                                    class="h-10 w-10 rounded-full object-cover border-2 border-primary-100">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo htmlspecialchars($user['fullName']); ?>
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    ID: 6715763708
                                </p>
                            </div>
                        </div>


                        <!-- KYC Verification Status -->
                        <div class="mb-3">
                            <div
                                class="flex items-center justify-center py-1 rounded-md bg-green-50 border border-green-100">
                                <span class="text-xs text-green-800 font-medium flex items-center">
                                    <i data-lucide="check-circle" class="h-3 w-3 mr-1"></i> KYC Verified
                                </span>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="settings.php"
                                class="flex-1 inline-flex justify-center items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i data-lucide="user" class="h-3 w-3 mr-1"></i> Profile
                            </a>
                            <a href="logout.php"
                                onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                                class="flex-1 inline-flex justify-center items-center px-2.5 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-primary-600 hover:bg-primary-700">
                                <i data-lucide="log-out" class="h-3 w-3 mr-1"></i> Logout
                            </a>
                            <form id="logout-form-sidebar" action="logout.php"
                                method="POST" style="display: none;">
                                <input type="hidden" name="_token" value="0SAjciz9kyWYU4TGSGc3j4xTu2Q4xSWNPBIshJPY">
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <nav class="flex-1 px-4 space-y-1" style="background-color: ;">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">Main Menu</p>

                    <a href="dash.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-lucide="home" class="mr-3 h-5 w-5 text-black"></i>
                        Dashboard
                    </a>

                    <a href="transactions.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-lucide="activity" class="mr-3 h-5 w-5 text-gray-500"></i>
                        Transactions
                    </a>

                    <!-- Cards Menu Item -->
        

                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">Transfers</p>

                    <a href="transfer.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-lucide="send" class="mr-3 h-5 w-5 text-gray-500"></i>
                        Local Transfer
                    </a>

                  

                    <a href="dash.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-lucide="download" class="mr-3 h-5 w-5 text-gray-500"></i>
                        Deposit
                    </a>

                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">Services</p>

                  

                    <a href="transactions.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-lucide="receipt" class="mr-3 h-5 w-5 text-gray-500"></i>
                        IRS Tax Refund
                    </a>

                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">Account</p>

                    <a href="settings.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg bg-primary-50 text-black border-l-4 border-primary-500 pl-2">
                        <i data-lucide="settings" class="mr-3 h-5 w-5 text-gray-500"></i>
                        Settings
                    </a>

                    <a href="settings.php"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i data-lucide="help-circle" class="mr-3 h-5 w-5 text-gray-500"></i>
                        Support Ticket
                    </a>
                </nav>
            </div>

            <!-- App Version -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i data-lucide="shield-check" class="h-4 w-4 text-green-500 mr-2"></i>
                        <span class="text-xs text-gray-500">Secure Banking</span>
                    </div>
                    <span class="text-xs text-gray-400">v1.2.0</span>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-20">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile: Logo + Menu button -->
                    <div class="flex items-center md:hidden">
                        <button @click="sidebarOpen = false; mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="text-gray-500 hover:text-gray-600 focus:outline-none" aria-label="Toggle menu">
                            <i data-lucide="menu" class="h-6 w-6"></i>
                        </button>
                        <a href="/" class="ml-4">
                            <img src="images/arffib.png"
                                alt="Logo" class="h-8 w-auto">
                        </a>
                    </div>

                    <!-- Desktop: Current Date & Time + Search bar -->
                    <div class="hidden md:flex md:flex-1 md:items-center">
                        <div class="text-sm text-gray-600 flex items-center">
                            <i data-lucide="calendar" class="h-4 w-4 mr-2 text-gray-400"></i>
                            <span>Saturday, July 26, 2025</span>
                        </div>
                    </div>

                    <!-- Right Nav Items (Both mobile & desktop) -->
                    <div class="flex items-center space-x-4">
                        <!-- Balance indicator (desktop only) -->
                        <div class="hidden md:flex items-center px-3 py-1.5 bg-primary-50 rounded-full">
                            <i data-lucide="wallet" class="h-4 w-4 text-gray-900 mr-2"></i>
                            <span class="text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars(number_format((float) ($user['balance'] ?? 0), 2, '.', ',')); ?> 
                            </span>
                        </div>

                        <!-- Notification Bell -->
                        <div class="relative" x-data="{ notificationsOpen: false }">
                            <button @click="notificationsOpen = !notificationsOpen; userDropdownOpen = false"
                                class="relative p-1 text-gray-500 hover:text-gray-600 focus:outline-none">
                                <i data-lucide="bell" class="h-6 w-6"></i>
                            </button>

                            <!-- Notification dropdown -->
                            <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                                class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                        <form
                                            action="transactions.php/mark-all-read"
                                            method="POST">
                                            <input type="hidden" name="_token"
                                                value="0SAjciz9kyWYU4TGSGc3j4xTu2Q4xSWNPBIshJPY"> <button type="submit"
                                                class="text-xs text-black hover:text-primary-500">Mark all as
                                                read</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Notification items -->
                                <div class="max-h-60 overflow-y-auto">

                                    <div class="py-6 text-center">
                                        <i data-lucide="inbox" class="h-8 w-8 mx-auto text-gray-300 mb-1"></i>
                                        <p class="text-sm text-gray-500">No notifications yet</p>
                                    </div>
                                </div>

                                <div class="px-4 py-3 border-t border-gray-100 text-center">
                                    <a href="transactions.php"
                                        class="text-sm font-medium text-black hover:text-primary-500">View all
                                        notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button @click="userDropdownOpen = !userDropdownOpen; notificationsOpen = false"
                                class="flex items-center max-w-xs text-sm rounded-full focus:outline-none"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>

                                <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200"
                                    src="<?php echo htmlspecialchars($displayProfileImage); ?>"
                                    alt="Testing">
                            </button>


                            <!-- User dropdown menu -->
                            <div x-show="userDropdownOpen" @click.away="userDropdownOpen = false"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['fullName']); ?></p>
                                    <p class="text-xs text-gray-500 mt-1">ID: 6715763708</p>

                                    <!-- KYC Verification Status -->
                                    <div class="mt-2 flex items-center">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 flex items-center">
                                            <i data-lucide="check-circle" class="h-3 w-3 mr-1"></i> Verified
                                        </span>
                                    </div>
                                </div>

                                <a href="settings.php"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center"
                                    role="menuitem">
                                    <i data-lucide="help-circle" class="h-4 w-4 mr-3 text-gray-500"></i> Support Ticket
                                </a>
                                <a href="settings.php"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center"
                                    role="menuitem">
                                    <i data-lucide="user" class="h-4 w-4 mr-3 text-gray-500"></i> My Profile
                                </a>
                                <a href="logout.php"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                                    class=" block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center"
                                    role="menuitem">
                                    <i data-lucide="log-out" class="h-4 w-4 mr-3 text-gray-500"></i> Sign Out
                                </a>
                                <form id="logout-form-header" action="logout.php"
                                    method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="0SAjciz9kyWYU4TGSGc3j4xTu2Q4xSWNPBIshJPY">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile Menu Popup - Centered Floating Box -->
            <div x-show="mobileMenuOpen" class="fixed inset-0 flex items-center justify-center z-40 md:hidden"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition-all ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" aria-hidden="true"
                    @click="mobileMenuOpen = false"></div>

                <!-- Popup Content - Centered Box -->
                <div class="relative w-11/12 max-w-md bg-white rounded-2xl shadow-2xl p-5 z-50">
                    <!-- Close button -->
                    <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500"
                        @click="mobileMenuOpen = false">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>

                    <!-- User info for mobile -->
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <div class="flex-shrink-0 mr-3">
                            <img src="<?php echo htmlspecialchars($displayProfileImage); ?>"
                                alt="Testing" class="h-12 w-12 rounded-full object-cover border-2 border-primary-100">
                        </div>

                        <div>
                            <h2 class="text-base font-semibold text-gray-900"><?php echo htmlspecialchars($user['fullName']); ?></h2>
                            <p class="text-sm text-gray-500">Account: 6715763708</p>

                            <!-- KYC Verification Status -->
                            <div class="mt-1">
                                <span
                                    class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 inline-flex items-center">
                                    <i data-lucide="check-circle" class="h-3 w-3 mr-1"></i> Verified
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Title -->
                    <div class="text-center mb-5">
                        <h2 class="text-xl font-bold text-gray-800">Banking Menu</h2>
                        <p class="text-sm text-gray-500">Select an option to continue</p>
                    </div>

                    <!-- Grid Menu - 3x3 Grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <a href="dash.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 hover:from-primary-100 hover:to-primary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="home" class="h-5 w-5 text-black"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Home</span>
                            </div>
                        </a>

                        <a href="transactions.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-secondary-50 to-secondary-100 hover:from-secondary-100 hover:to-secondary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="activity" class="h-5 w-5 text-secondary-600"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Activity</span>
                            </div>
                        </a>

                        

                        <a href="transfer.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-secondary-50 to-secondary-100 hover:from-secondary-100 hover:to-secondary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="send" class="h-5 w-5 text-secondary-600"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Transfer</span>
                            </div>
                        </a>

                        

                        <a href="dash.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 hover:from-primary-100 hover:to-primary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="download" class="h-5 w-5 text-black"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Deposit</span>
                            </div>
                        </a>

                        <a href="dash.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-secondary-50 to-secondary-100 hover:from-secondary-100 hover:to-secondary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="credit-card" class="h-5 w-5 text-secondary-600"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Loan</span>
                            </div>
                        </a>

                        <a href="transactions.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 hover:from-primary-100 hover:to-primary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="receipt" class="h-5 w-5 text-black"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">IRS Refund</span>
                            </div>
                        </a>

                        <a href="settings.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 hover:from-primary-100 hover:to-primary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="settings" class="h-5 w-5 text-black"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Settings</span>
                            </div>
                        </a>

                        <a href="settings.php" class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-secondary-50 to-secondary-100 hover:from-secondary-100 hover:to-secondary-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="help-circle" class="h-5 w-5 text-secondary-600"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Support</span>
                            </div>
                        </a>

                        <a href="logout.php"
                            onclick="event.preventDefault(); document.getElementById('logout-form-grid').submit();"
                            class="group">
                            <div
                                class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gradient-to-br from-accent-50 to-accent-100 hover:from-accent-100 hover:to-accent-200 transition-all duration-300 p-2">
                                <div
                                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center mb-1 shadow-sm group-hover:shadow transition-all">
                                    <i data-lucide="log-out" class="h-5 w-5 text-accent-600"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Logout</span>
                            </div>
                        </a>
                        <form id="logout-form-grid" action="logout.php" method="POST"
                            style="display: none;">
                            <input type="hidden" name="_token" value="0SAjciz9kyWYU4TGSGc3j4xTu2Q4xSWNPBIshJPY">
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Bar - Enhanced Design -->
            <div class="fixed bottom-0 left-0 right-0 md:hidden z-30">
                <!-- Main Navigation Bar -->
                <div class="bg-white border-t border-gray-200 shadow-lg rounded-t-3xl mx-2 mb-1">
                    <div class="flex justify-between items-center px-6 py-3 relative">
                        <a href="dash.php" class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                <i data-lucide="home" class="h-5 w-5 text-primary-600"></i>
                            </div>
                            <span class="text-xs font-medium text-primary-600">Home</span>
                        </a>

                        <a href="transactions.php"
                            class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                <i data-lucide="bar-chart-2" class="h-5 w-5 text-gray-500"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Stats</span>
                        </a>

                        <!-- Center Button - Floating Action Button -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 top-0">
                            <button @click="mobileMenuOpen = true"
                                class="bg-gradient-to-r from-primary-600 to-primary-800 w-16 h-16 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                <i data-lucide="grid" class="h-8 w-8 text-white"></i>
                            </button>
                        </div>

                        <a href="dash.php" class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                <i data-lucide="credit-card" class="h-5 w-5 text-gray-500"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Cards</span>
                        </a>

                        <a href="settings.php"
                            class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="h-5 w-5 text-gray-500"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Profile</span>
                        </a>
                    </div>
                </div>
            </div>












            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto pb-16 md:pb-0">
                <div class="py-6">
                    <div class="max-w-8xl mx-auto px-4 sm:px-6 md:px-8">
                        
<div x-data="{
    showProfilePictureModal: false,
    showTransactionPinModal: false
}">
    <!-- Alerts -->
            <div>
                <?php if ($error !== ''): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <?php if ($success !== ''): ?>
                    <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700" role="alert">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
            </div>
    <!-- Page Header with Breadcrumbs -->
    <div class="flex flex-col mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Account Settings</h1>
            <div class="flex items-center text-sm text-gray-500">
                <a href="dash.php" class="hover:text-primary-600">Dashboard</a>
                <i data-lucide="chevron-right" class="h-4 w-4 mx-2"></i>
                <span class="font-medium text-gray-700">Settings</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column - Profile Card and Navigation -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mb-6">
                <!-- Profile Photo and Name Header -->
                <div class="relative bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-8 flex flex-col items-center">
                    <!-- Profile Photo -->
                    <div class="relative mb-3">
                        <div class="h-24 w-24 rounded-full border-4 border-white/50 overflow-hidden bg-white shadow-md">
                            <img
                                src="../<?php echo htmlspecialchars($displayProfileImage); ?>"
                                class="h-full w-full object-cover"
                                alt="Handrik"
                                onerror="this.src='https://ui-avatars.com/api/?name=Handrik&background=random'"
                            />
                        </div>
                        <button
                            @click="showProfilePictureModal = true"
                            class="absolute -bottom-1 -right-1 h-8 w-8 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-gray-100 transition-colors"
                        >
                            <i data-lucide="camera" class="h-4 w-4 text-primary-600"></i>
                        </button>
                    </div>

                    <!-- User Name -->
                    <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($user['fullName']); ?></h2>
                    <p class="text-white/80 text-sm">Account #6715763708</p>

                    <!-- Wave decoration at the bottom -->
                    <div class="absolute bottom-0 left-0 right-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="h-6 w-full text-white fill-current">
                            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <div class="p-4">
                    <nav class="space-y-1">
                        <a
                            href="settings.php"
                            class="flex items-center px-4 py-3 rounded-lg bg-primary-50 text-gray-700 font-medium"
                        >
                            <i data-lucide="user" class="h-5 w-5 mr-3 text-gray-600"></i>
                            <span>Profile Information</span>
                        </a>

                        <a
                            href="edit.php"
                            class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
                        >
                            <i data-lucide="shield" class="h-5 w-5 mr-3 text-gray-500"></i>
                            <span>Security Settings</span>
                        </a>

                        <button
                            @click="showTransactionPinModal = true"
                            class="w-full flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 font-medium text-left"
                        >
                            <i data-lucide="key" class="h-5 w-5 mr-3 text-gray-600"></i>
                            <span>Transaction PIN</span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Contact Support Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden p-5">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                        <i data-lucide="help-circle" class="h-5 w-5 text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Need Help?</h3>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Contact our support team if you need assistance with your account settings or have any questions.
                </p>
                <a
                    href="dash.php"
                    class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm font-medium"
                >
                    Contact Support
                    <i data-lucide="arrow-right" class="h-4 w-4 ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Right Column - Main Content Area -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <!-- Content Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i data-lucide="user" class="h-5 w-5 mr-2 text-primary-600"></i>
                        Profile Information
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Your personal information and account details
                    </p>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <form action="#" method="post">
                        <!-- Two Column Layout for Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <!-- First Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-gray-700"
                                        value="<?php echo htmlspecialchars($user['fullName']); ?>"
                                        readonly
                                        autocomplete="name"
                                    />
                                </div>
                            </div>

                            <!-- Last Name -->
                            
                        </div>

                        <!-- Account Number -->
                        <div class="mb-6">
                            <label for="accountnumber" class="block text-sm font-medium text-gray-700 mb-1">
                                Account Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="hash" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    type="text"
                                    id="accountnumber"
                                    class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-gray-700"
                                    value="6715763708"
                                    readonly
                                    autocomplete="off"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button
                                        type="button"
                                        class="text-gray-400 hover:text-primary-600 focus:outline-none"
                                        onclick="navigator.clipboard.writeText('6715763708').then(() => alert('Account number copied to clipboard!'))"
                                    >
                                        <i data-lucide="copy" class="h-5 w-5"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">This is your unique account identifier</p>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-gray-700"
                                    value="<?php echo htmlspecialchars($user['email']); ?>"
                                    readonly
                                    autocomplete="email"
                                />
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-6">
                            <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Birth
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="calendar" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    type="date"
                                    id="dob"
                                    name="dob"
                                    class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-gray-700"
                                    value="<?php echo htmlspecialchars($user['dob']); ?>"
                                    readonly
                                    autocomplete="bday"
                                />
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="phone" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-gray-700"
                                    value="+<?php echo htmlspecialchars($user['phone']); ?>"
                                    readonly
                                    autocomplete="tel"
                                />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                Address
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                    <i data-lucide="map-pin" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <textarea
                                    id="address"
                                    name="address"
                                    rows="3"
                                    class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none text-gray-700"
                                    readonly
                                    autocomplete="street-address"
                                ><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                        </div>

                        <!-- Information Card -->
                        <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-100">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i data-lucide="info" class="h-5 w-5 text-blue-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Account Information</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>
                                            To update your personal information, please contact our customer support team with your request.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Picture Upload Modal -->
    <div
        x-show="showProfilePictureModal"
        x-cloak
        class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            @click="showProfilePictureModal = false"
        ></div>

        <div
            class="relative bg-white rounded-lg w-full max-w-md mx-4 shadow-xl transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-900">Upload Profile Picture</h3>
                    <button @click="showProfilePictureModal = false" class="text-gray-400 hover:text-gray-500">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form action="settings.php" method="post" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(transfer_csrf_token()); ?>">
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">
                            Select New Profile Picture
                        </label>
                        <div class="mt-2">
                            <div class="flex items-center justify-center w-full">
                                <label for="photo-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i data-lucide="upload-cloud" class="h-10 w-10 text-gray-400 mb-2"></i>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                                    </div>
                                    <input id="photo-upload" type="file" name="photo" class="hidden" required />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full px-4 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
                        >
                            <i data-lucide="upload" class="h-5 w-5 inline mr-2"></i>
                            Upload Profile Picture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction PIN Modal -->
    <div
        x-show="showTransactionPinModal"
        x-cloak
        class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            @click="showTransactionPinModal = false"
        ></div>

        <div
            class="relative bg-white rounded-lg w-full max-w-md mx-4 shadow-xl transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-900">Change Transaction PIN</h3>
                    <button @click="showTransactionPinModal = false" class="text-gray-400 hover:text-gray-500">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form action="settings.php" method="post" class="space-y-5">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(transfer_csrf_token()); ?>">
                    <!-- New Transaction PIN -->
                    <div>
                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-1">
                            New Transaction PIN
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="key" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input
                                type="password"
                                id="pin"
                                name="pin"
                                class="block w-full pl-10 py-3 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="Enter new transaction PIN"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Create a secure PIN that you can remember</p>
                    </div>

                    <!-- Account Password for Verification -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Current Password
                        </label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                name="current_password"
                                class="block w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="Enter your current password"
                                required
                                autocomplete="current-password"
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="text-gray-400 hover:text-primary-600 focus:outline-none"
                                >
                                    <i x-show="!showPassword" data-lucide="eye" class="h-5 w-5"></i>
                                    <i x-show="showPassword" data-lucide="eye-off" class="h-5 w-5"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">For security verification</p>
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Security Alert</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>
                                        Keep your transaction PIN confidential. Never share your PIN with anyone, including bank representatives.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full px-4 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
                        >
                            <i data-lucide="rotate-cw" class="h-5 w-5 inline mr-2"></i>
                            Update Transaction PIN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();

        // Preview selected profile image
        const photoUpload = document.getElementById('photo-upload');
        if (photoUpload) {
            photoUpload.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const parent = e.target.parentElement;
                    const children = parent.querySelectorAll(':not(input)');

                    // Hide the default content
                    children.forEach(child => child.style.display = 'none');

                    // Create image preview
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(e.target.files[0]);
                    img.classList.add('h-full', 'w-full', 'object-cover', 'rounded-lg');
                    parent.appendChild(img);
                }
            });
        }
    });
</script>

                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 hidden md:block">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 md:flex md:items-center md:justify-between">
                    <div class="flex items-center">
                        <img src="images/arffib.png" alt="Logo" class="h-6 w-auto mr-2">
                        <p class="text-sm text-gray-500">© 2025 UW CREDIT UNION. All rights reserved.</p>
                    </div>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="dash.php" class="text-sm text-gray-500 hover:text-gray-700">Privacy Policy</a>
                        <a href="dash.php" class="text-sm text-gray-500 hover:text-gray-700">Terms of Service</a>
                        <a href="settings.php" class="text-sm text-gray-500 hover:text-gray-700">Contact Support</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <style>
        .chaport-container{
            bottom: 80px !important;
        }
    </style>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
<!-- Begin of Chaport Live Chat code -->
<!-- <script type="text/javascript">
(function(w,d,v3){
w.chaportConfig = {
  appId : '68560630c8bc0005dd8796dd'
};

if(w.chaport)return;v3=w.chaport={};v3._q=[];v3._l={};v3.q=function(){v3._q.push(arguments)};v3.on=function(e,fn){if(!v3._l[e])v3._l[e]=[];v3._l[e].push(fn)};var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://app.chaport.com/javascripts/insert.js';var ss=d.getElementsByTagName('script')[0];ss.parentNode.insertBefore(s,ss)})(window, document);
</script> -->
<!-- End of Chaport Live Chat code -->
    <!-- Enhanced Page Loading Animation -->
    <script>
        window.onload = function() {
            const preloader = document.querySelector('.page-loading');

            // Add a slight delay to make loading animation more noticeable
            setTimeout(function() {
                preloader.classList.remove('active');
                setTimeout(function() {
                    preloader.remove();
                }, 500);
            }, 800);
        };
    </script>

    <!-- Date and Time Updates -->
    <script>
        // Function to update current time
        function updateDateTime() {
            const now = new Date();
            const timeElements = document.querySelectorAll('[data-current-time]');
            const dateElements = document.querySelectorAll('[data-current-date]');

            if (timeElements.length > 0) {
                const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                timeElements.forEach(el => {
                    el.textContent = timeString;
                });
            }

            if (dateElements.length > 0) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const dateString = now.toLocaleDateString(undefined, options);
                dateElements.forEach(el => {
                    el.textContent = dateString;
                });
            }
        }

        // Update time every minute
        updateDateTime();
        setInterval(updateDateTime, 60000);
    </script>

   


            <!--<div class="gtranslate_wrapper"></div>
<script>
    window.gtranslateSettings = {
        default_language: "en",
        alt_flags:{"en":"usa"},
        wrapper_selector: ".gtranslate_wrapper",
        flag_style: "3d",
    };
</script>
<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>
-->


  
</body>
</html>
