<?php
require_once __DIR__ . '/auth.php';
require_auth();

$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbname = "bank_system";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
if (!is_numeric($user_id)) {
    $user_id = null;
}

$user = [
    'id' => 0,
    'fullName' => 'Guest Customer',
    'balance' => '0.00',
    'idDocument' => 'images/uw.png',
    'profileImage' => '',
    'email' => '',
    'accountType' => 'Savings',
    'branch' => 'Main Branch',
    'phone' => ''
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
    if (!empty($user['profileImage']) && $user['profileImage'] !== 'images/uw.png') {
        return $user['profileImage'];
    }
    return $user['idDocument'] ?? 'images/uw.png';
}

$displayProfileImage = get_profile_image($user);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="0SAjciz9kyWYU4TGSGc3j4xTu2Q4xSWNPBIshJPY">
    <title>UW CREDIT UNION | Customer Dashboard</title>
    <meta name="description"
        content="Swift and Secure Money Transfer to any UK bank account will become a breeze with UW CREDIT UNION." />
    <link rel="shortcut icon"
        href="images/uw.png" />
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

<style>
     
</style>
    <!-- Modern Loading Animation -->
    <style>
        body{
            background-color: black;
        }
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

        @keyframes rotateCircle {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            from {
                transform: scale(0.8);
                opacity: 0.8;
            }

            to {
                transform: scale(1.2);
                opacity: 1;
            }
        }

        @keyframes gradient {
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
    <link rel="manifest" href="images/uw.png">
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


    <link href="/images/icons/splash-640x1136.png"
        media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-750x1334.png"
        media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1242x2208.png"
        media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1125x2436.png"
        media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-828x1792.png"
        media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1242x2688.png"
        media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1536x2048.png"
        media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1668x2224.png"
        media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-1668x2388.png"
        media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />
    <link href="/images/icons/splash-2048x2732.png"
        media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)"
        rel="apple-touch-startup-image" />

    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/icons/icon-512x512.png">

 
</head>

<body class="bg-gray-50" style="background-color: ;">

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
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg bg-primary-50 text-black border-l-4 border-primary-500 pl-2">
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

                  

                    <a href="deposit.php"
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
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
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
                            <img src="images/uw.png"
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

                        

                        <a href="deposit.php" class="group">
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
    showBankAccount: false,
    showSendMoney: false,
    currentTime: '',
    greeting: '',
    currentDate: '',
    balanceVisible: true,
    toggleBalance() {
        this.balanceVisible = !this.balanceVisible;
    },
    updateTime() {
        const now = new Date();

        // Format the time (HH:MM:SS)
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        this.currentTime = `${hours}:${minutes}:${seconds}`;

        // Set greeting based on hours
        if (now.getHours() < 12) {
            this.greeting = 'Good Morning';
        } else if (now.getHours() < 18) {
            this.greeting = 'Good Afternoon';
        } else {
            this.greeting = 'Good Evening';
        }

        // Format the date (Day, Month Date, Year)
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        this.currentDate = now.toLocaleDateString(undefined, options);
    }
}" x-init="
    updateTime();
    setInterval(() => updateTime(), 1000);
">
                            <!-- Alerts -->

                            <!-- Top Stats Summary Bar -->
                            <div class="hidden lg:grid grid-cols-4 gap-4 mb-6">
                                <div
                                    class="bg-gradient-to-r from-primary-50 to-white rounded-xl p-4 border border-primary-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-800">Current Balance</p>
                                        <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars(number_format((float) ($user['balance'] ?? 0), 2, '.', ',')); ?> </p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <i data-lucide="wallet" class="h-5 w-5 text-gray-800"></i>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-green-50 to-white rounded-xl p-4 border border-green-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">Monthly Income</p>
                                        <p class="text-lg font-bold text-green-700">£0</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <i data-lucide="trending-up" class="h-5 w-5 text-green-600"></i>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-red-50 to-white rounded-xl p-4 border border-red-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">Monthly Outgoing</p>
                                        <p class="text-lg font-bold text-red-700">£0</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <i data-lucide="trending-down" class="h-5 w-5 text-red-600"></i>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-white rounded-xl p-4 border border-purple-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">Transaction Limit</p>
                                        <p class="text-lg font-bold text-purple-700">£500,000.00</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i data-lucide="gauge" class="h-5 w-5 text-purple-600"></i>
                                    </div>
                                </div>
                            </div>


                            <!-- Main Dashboard Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Left Column - Balance and Quick Actions -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Balance Card with Interactive Elements -->
                                    <div
                                        class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 rounded-2xl shadow-lg text-white relative overflow-hidden">
                                        <!-- Day/Night Decoration -->
                                        <div class="absolute inset-0 w-full h-full overflow-hidden">
                                            <div class="absolute opacity-20 right-0 top-0">
                                                <div class="bg-yellow-300 rounded-full h-32 w-32 -mt-10 -mr-10 blur-xl">
                                                </div>
                                            </div>
                                            <div class="absolute opacity-10 left-1/2 top-1/2">
                                                <div class="bg-yellow-200 rounded-full h-40 w-40 blur-xl"></div>
                                            </div>
                                            <!-- Daytime clouds -->
                                            <div class="absolute opacity-10 left-0 bottom-0">
                                                <i data-lucide="cloud" class="h-16 w-16 text-white"></i>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="relative z-10 p-6">
                                            <!-- Header with time and user -->
                                            <div class="flex items-center justify-between mb-6">
                                                <div class="flex items-center space-x-3">
                                                    <img alt="Testing"
                                                        src="<?php echo htmlspecialchars($displayProfileImage); ?>"
                                                        class="h-12 w-12 rounded-full object-cover border-2 border-white/20">
                                                    <div>
                                                        <div class="text-sm text-white/80" x-text="greeting"></div>
                                                        <div class="font-medium text-white"><?php echo htmlspecialchars($user['fullName']); ?></div>
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <div class="text-lg font-bold" x-text="currentTime"></div>
                                                    <div class="text-xs text-white/70" x-text="currentDate"></div>
                                                </div>
                                            </div>

                                            <!-- Balance with hide/show toggle -->
                                            <div class="mb-6">
                                                <div class="flex items-center justify-between">
                                                    <h2 class="text-lg font-medium mb-1">Available Balance</h2>
                                                    <button @click="toggleBalance()"
                                                        class="text-white/80 hover:text-white focus:outline-none transition-all">
                                                        <i x-show="balanceVisible" data-lucide="eye-off"
                                                            class="h-5 w-5"></i>
                                                        <i x-show="!balanceVisible" data-lucide="eye"
                                                            class="h-5 w-5"></i>
                                                    </button>
                                                </div>
                                                <div x-show="balanceVisible" x-transition class="text-3xl font-bold">
                                                    <?php echo htmlspecialchars(number_format((float) ($user['balance'] ?? 0), 2, '.', ',')); ?> USD
                                                </div>
                                                <div x-show="!balanceVisible" x-transition class="text-3xl font-bold">
                                                    *******
                                                </div>
                                            </div>

                                            <!-- Account Info Bar -->
                                            <div class="relative z-10 p-4 bg-white/10 rounded-lg backdrop-blur-sm">
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                                    <!-- Mobile layout (side-by-side) -->
                                                    <div class="sm:hidden flex items-center justify-between w-full">
                                                        <div class="flex items-center flex-1 min-w-0">
                                                            <div class="flex-shrink-0 mr-3">
                                                                <div
                                                                    class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center">
                                                                    <i data-lucide="shield"
                                                                        class="h-5 w-5 text-white"></i>
                                                                </div>
                                                            </div>
                                                            <div class="truncate">
                                                                <div class="text-sm font-medium">Your Account Number
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <div class="text-lg font-bold truncate mr-2">
                                                                        6715763708</div>
                                                                    <div class="flex-shrink-0">
                                                                        <span
                                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            <span
                                                                                class="h-1.5 w-1.5 rounded-full bg-green-600 mr-1"></span>
                                                                            Active
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex flex-col gap-2 ml-2">
                                                            <a href="transactions.php"
                                                                class="inline-flex items-center justify-center px-2 py-1 bg-white text-primary-600 text-xs font-medium rounded-md hover:bg-gray-50">
                                                                <i data-lucide="activity" class="h-3 w-3 mr-1"></i>
                                                                Transactions
                                                            </a>
                                                            <a href="dash.php"
                                                                class="inline-flex items-center justify-center px-2 py-1 bg-primary-700 text-white text-xs font-medium rounded-md hover:bg-primary-800 border border-white/10">
                                                                <i data-lucide="wallet" class="h-3 w-3 mr-1"></i> Top up
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <!-- Desktop layout - hidden on mobile -->
                                                    <div class="hidden sm:flex sm:items-center sm:flex-1">
                                                        <div class="flex-shrink-0 mr-4">
                                                            <div
                                                                class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center">
                                                                <i data-lucide="shield" class="h-5 w-5 text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="flex items-center">
                                                                <div class="text-sm font-medium mr-2">Your Account
                                                                    Number</div>
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    <span
                                                                        class="h-1.5 w-1.5 rounded-full bg-green-600 mr-1"></span>
                                                                    Active
                                                                </span>
                                                            </div>
                                                            <div class="text-lg font-bold">6715763708</div>
                                                        </div>
                                                    </div>

                                                    <!-- Original desktop buttons - hidden on mobile -->
                                                    <div class="hidden sm:flex sm:flex-row gap-2">
                                                        <a href="transactions.php"
                                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-white text-primary-600 text-sm font-medium rounded-md hover:bg-gray-50">
                                                            <i data-lucide="activity" class="h-4 w-4 mr-1"></i>
                                                            Transactions
                                                        </a>
                                                        <a href="dash.php"
                                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-primary-700 text-white text-sm font-medium rounded-md hover:bg-primary-800 border border-white/10">
                                                            <i data-lucide="wallet" class="h-4 w-4 mr-1"></i> Top up
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Welcome and Quick Actions Card -->
                                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                                            <div>
                                                <h1 class="text-xl font-bold mb-1">What would you like to do today?</h1>
                                                <p class="text-gray-600">Choose from our popular actions below</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <button @click="showBankAccount = true"
                                                class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 border border-gray-200 transition-all">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mb-3">
                                                    <i data-lucide="building-2" class="h-6 w-6 text-gray-600"></i>
                                                </div>
                                                <span class="font-medium text-gray-800">Account Info</span>
                                            </button>

                                            <button @click="showSendMoney = true"
                                                class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 hover:from-primary-100 hover:to-primary-200 border border-primary-200 transition-all">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-3">
                                                    <i data-lucide="send" class="h-6 w-6 text-gray-600"></i>
                                                </div>
                                                <span class="font-medium text-gray-800">Send Money</span>
                                            </button>

                                            <a href="dash.php"
                                                class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 border border-green-200 transition-all">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-3">
                                                    <i data-lucide="plus" class="h-6 w-6 text-green-600"></i>
                                                </div>
                                                <span class="font-medium text-gray-800">Deposit</span>
                                            </a>

                                            <a href="transactions.php"
                                                class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 border border-purple-200 transition-all">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3">
                                                    <i data-lucide="history" class="h-6 w-6 text-purple-600"></i>
                                                </div>
                                                <span class="font-medium text-gray-800">History</span>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Cards Section to add to the Dashboard -->
                                  

                                    <!-- Recent Transactions Card -->
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                                        <div
                                            class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <i data-lucide="list" class="h-5 w-5 text-gray-500 mr-2"></i>
                                                <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
                                            </div>
                                            <a href="transactions.php"
                                                class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center">
                                                View all <i data-lucide="chevron-right" class="h-4 w-4 ml-1"></i>
                                            </a>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Amount</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Type</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Status</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Reference ID</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Created</th>
                                                    </tr>
                                                </thead>
                                                <!-- <tbody class="bg-white divide-y divide-gray-200">

                                                    <tr class="hover:bg-gray-50 cursor-pointer" id="1028MET-287e470cfe">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div
                                                                class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                                                <i data-lucide="minus" class="h-5 w-5 text-red-600"></i>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">£500.46 USD
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Debit
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            MET-287e470cfe</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6
                                                            days ago</td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50 cursor-pointer" id="1027MET-d3b1b86cec">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div
                                                                class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                                <i data-lucide="plus"
                                                                    class="h-5 w-5 text-green-600"></i>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">£101,000.00
                                                                USD</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Credit
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Processed
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            MET-d3b1b86cec</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1
                                                            week ago</td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50 cursor-pointer" id="1025MET-0f479498fb">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div
                                                                class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                                <i data-lucide="plus"
                                                                    class="h-5 w-5 text-green-600"></i>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">£10,000.00
                                                                USD</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Credit
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Processed
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            MET-0f479498fb</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1
                                                            week ago</td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50 cursor-pointer" id="1026MET-342f6d922d">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div
                                                                class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                                                <i data-lucide="minus" class="h-5 w-5 text-red-600"></i>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">£100,000.00
                                                                USD</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Debit
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            MET-342f6d922d</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1
                                                            week ago</td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50 cursor-pointer" id="1024MET-4bfc62d0d8">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div
                                                                class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                                                <i data-lucide="minus" class="h-5 w-5 text-red-600"></i>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">£10,000.00
                                                                USD</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Debit
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            MET-4bfc62d0d8</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1
                                                            week ago</td>
                                                    </tr>
                                                </tbody> -->
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - Stats and Notices -->
                                <div class="space-y-6">
                                    <!-- Account Stats Card -->
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <h3 class="text-lg font-medium text-gray-900">Account Statistics</h3>
                                        </div>

                                        <div class="p-6 space-y-4">
                                            <!-- Transaction Limit -->
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-4">
                                                    <i data-lucide="credit-card" class="h-5 w-5 text-gray-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-500">Transaction Limit</p>
                                                    <p class="text-lg font-bold text-gray-900 truncate">£500,000.00</p>
                                                </div>
                                            </div>

                                            <!-- Pending Transactions -->
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                                                    <i data-lucide="clock" class="h-5 w-5 text-yellow-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-500">Pending Transactions</p>
                                                    <p class="text-lg font-bold text-gray-900 truncate">£451,000.92</p>
                                                </div>
                                            </div>

                                            <!-- Total Transaction Volume -->
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                                    <i data-lucide="bar-chart-2" class="h-5 w-5 text-green-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-500">Transaction Volume</p>
                                                    <p class="text-lg font-bold text-gray-900 truncate">£504,438,534.62
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Account Age -->
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                                                    <i data-lucide="calendar" class="h-5 w-5 text-purple-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-500">Account Age</p>
                                                    <p class="text-lg font-bold text-gray-900 truncate">3 weeks</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Transfer Links Card -->
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <h3 class="text-lg font-medium text-gray-900">Quick Transfer</h3>
                                        </div>

                                        <div class="p-6 space-y-4">
                                            <a href="transfer.php"
                                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 mr-4">
                                                        <div
                                                            class="h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center">
                                                            <i data-lucide="user" class="h-5 w-5 text-gray-600"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">Local Transfer</h4>
                                                        <p class="text-sm text-gray-600">0% Handling charges</p>
                                                    </div>
                                                </div>
                                                <i data-lucide="chevron-right" class="h-5 w-5 text-gray-400"></i>
                                            </a>

                                         
                                        </div>
                                    </div>

                                    <!-- Help & Support Card -->
                                    <div
                                        class="bg-gradient-to-br from-primary-50 via-primary-100 to-primary-50 rounded-xl shadow-sm overflow-hidden border border-primary-200">
                                        <div class="p-6">
                                            <div class="flex items-center justify-center mb-4">
                                                <div
                                                    class="h-16 w-16 rounded-full bg-white flex items-center justify-center">
                                                    <i data-lucide="help-circle" class="h-10 w-10 text-primary-600"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Need Help?
                                            </h3>
                                            <p class="text-sm text-gray-600 text-center mb-4">Our support team is here
                                                to assist you 24/7</p>
                                            <div class="flex justify-center">
                                                <a href="settings.php"
                                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                                                    <i data-lucide="message-circle" class="h-4 w-4 mr-2"></i> Contact
                                                    Support
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Account Modal -->
                            <div x-show="showBankAccount" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="bank-account-title"
                                role="dialog" aria-modal="true">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showBankAccount" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"
                                        @click="showBankAccount = false" aria-hidden="true">
                                    </div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div x-show="showBankAccount" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="inline-block align-bottom bg-white rounded-xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                                        <div class="absolute top-0 right-0 pt-4 pr-4">
                                            <button @click="showBankAccount = false" type="button"
                                                class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <span class="sr-only">Close</span>
                                                <i data-lucide="x" class="h-6 w-6"></i>
                                            </button>
                                        </div>

                                        <div class="text-center mb-5">
                                            <div
                                                class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 mb-4">
                                                <i data-lucide="building-2" class="h-8 w-8 text-primary-600"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900" id="bank-account-title">Bank
                                                Account Details</h3>
                                            <p class="mt-1 text-sm text-gray-500">UW CREDIT UNION</p>
                                            <p class="text-xs text-gray-500">301 East Water Street, Charlottesville, VA
                                                22904 Virginia</p>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                            <p class="font-medium mb-3 flex items-center"><i data-lucide="info"
                                                    class="h-4 w-4 mr-2 text-primary-500"></i> Account Details</p>
                                            <ul class="space-y-3">
                                                <li
                                                    class="flex items-center justify-between p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <div class="flex items-center">
                                                        <div class="h-2 w-2 bg-primary-500 rounded-full mr-3"></div>
                                                        <span class="text-sm text-gray-700">Account Name</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium"><?php echo htmlspecialchars($user['fullName']); ?></span>
                                                        <button
                                                            class="ml-2 text-primary-500 hover:text-primary-700 focus:outline-none"
                                                            @click="navigator.clipboard.writeText('<?php echo htmlspecialchars($user['fullName']); ?>'); $el.querySelector('i').classList.add('text-green-500')">
                                                            <i data-lucide="copy"
                                                                class="h-4 w-4 transition-colors duration-300"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li
                                                    class="flex items-center justify-between p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <div class="flex items-center">
                                                        <div class="h-2 w-2 bg-primary-500 rounded-full mr-3"></div>
                                                        <span class="text-sm text-gray-700">Account Number</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium">6715763708</span>
                                                        <button
                                                            class="ml-2 text-primary-500 hover:text-primary-700 focus:outline-none"
                                                            @click="navigator.clipboard.writeText('6715763708'); $el.querySelector('i').classList.add('text-green-500')">
                                                            <i data-lucide="copy"
                                                                class="h-4 w-4 transition-colors duration-300"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li
                                                    class="flex items-center justify-between p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <div class="flex items-center">
                                                        <div class="h-2 w-2 bg-primary-500 rounded-full mr-3"></div>
                                                        <span class="text-sm text-gray-700">Sort Code</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium">388130</span>
                                                        <button
                                                            class="ml-2 text-primary-500 hover:text-primary-700 focus:outline-none"
                                                            @click="navigator.clipboard.writeText('388130'); $el.querySelector('i').classList.add('text-green-500')">
                                                            <i data-lucide="copy"
                                                                class="h-4 w-4 transition-colors duration-300"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li
                                                    class="flex items-center justify-between p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <div class="flex items-center">
                                                        <div class="h-2 w-2 bg-primary-500 rounded-full mr-3"></div>
                                                        <span class="text-sm text-gray-700">Payment Reference</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium">6490876432</span>
                                                        <button
                                                            class="ml-2 text-primary-500 hover:text-primary-700 focus:outline-none"
                                                            @click="navigator.clipboard.writeText('6490876432'); $el.querySelector('i').classList.add('text-green-500')">
                                                            <i data-lucide="copy"
                                                                class="h-4 w-4 transition-colors duration-300"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="flex items-start p-4 bg-primary-50 rounded-lg">
                                            <i data-lucide="info"
                                                class="h-5 w-5 text-primary-500 mt-0.5 mr-3 flex-shrink-0"></i>
                                            <p class="text-sm text-gray-700">
                                                Payment reference helps UW CREDIT UNION track payments faster.
                                                Please include it in wire transfer description.
                                            </p>
                                        </div>

                                        <div class="mt-6 flex justify-end">
                                            <button @click="showBankAccount = false"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Send Money Modal -->
                            <div x-show="showSendMoney" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="send-money-title"
                                role="dialog" aria-modal="true">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showSendMoney" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity"
                                        @click="showSendMoney = false" aria-hidden="true">
                                    </div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div x-show="showSendMoney" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="inline-block align-bottom bg-white rounded-xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                                        <div class="absolute top-0 right-0 pt-4 pr-4">
                                            <button @click="showSendMoney = false" type="button"
                                                class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <span class="sr-only">Close</span>
                                                <i data-lucide="x" class="h-6 w-6"></i>
                                            </button>
                                        </div>

                                        <div class="text-center mb-5">
                                            <div
                                                class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 mb-4">
                                                <i data-lucide="send" class="h-8 w-8 text-primary-600"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900" id="send-money-title">Send
                                                Money</h3>
                                            <p class="mt-1 text-sm text-gray-500">Swift and Secure Money Transfer</p>
                                        </div>

                                        <div class="mt-6 space-y-4">
                                            <a href="transfer.php"
                                                class="block group">
                                                <div
                                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors border border-gray-200">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 mr-4">
                                                            <div
                                                                class="h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                                                                <i data-lucide="user"
                                                                    class="h-5 w-5 text-primary-600"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-medium text-gray-900">Local Transfer</h4>
                                                            <p class="text-sm text-gray-600">Easily send money locally
                                                            </p>
                                                            <p class="text-xs text-gray-500">0% Handling charges</p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                                                        <i data-lucide="chevron-right"
                                                            class="h-5 w-5 text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                                                    </div>
                                                </div>
                                            </a>

                                            <a href="transfer.php"
                                                class="block group">
                                                <div
                                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg group-hover:bg-gray-100 transition-colors border border-gray-200">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 mr-4">
                                                            <div
                                                                class="h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                                                                <i data-lucide="globe"
                                                                    class="h-5 w-5 text-primary-600"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-medium text-gray-900">International Wire
                                                                Transfer</h4>
                                                            <p class="text-sm text-gray-600">Wire transfer is executed
                                                                under 72 hours</p>
                                                            <p class="text-xs text-gray-500">IBAN & SWIFT code required
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                                                        <i data-lucide="chevron-right"
                                                            class="h-5 w-5 text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="mt-6 flex justify-end">
                                            <button @click="showSendMoney = false"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            /* Card hover effects */
                            .group-hover\:scale-\[1\.02\] {
                                transform: scale(1.02);
                            }

                            /* Ensure rounded corners everywhere */
                            .rounded-xl {
                                border-radius: 0.75rem;
                            }

                            /* Shadow control */
                            .shadow-none {
                                box-shadow: none !important;
                            }

                            .group-hover\:shadow-md {
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                            }

                            /* Smooth transitions */
                            .transition-all {
                                transition-property: all;
                                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                                transition-duration: 300ms;
                            }

                            /* Responsive adjustments */
                            @media (max-width: 640px) {
                                .grid {
                                    gap: 1rem;
                                }
                            }
                        </style>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 hidden md:block">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 md:flex md:items-center md:justify-between">
                    <div class="flex items-center">
                        <img src="images/uw.png"
                            alt="Logo" class="h-6 w-auto mr-2">
                        <p class="text-sm text-gray-500">© 2025 UW CREDIT UNION. All rights reserved.</p>
                    </div>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="dash.php" class="text-sm text-gray-500 hover:text-gray-700">Privacy Policy</a>
                        <a href="dash.php" class="text-sm text-gray-500 hover:text-gray-700">Terms of Service</a>
                        <a href="settings.php"
                            class="text-sm text-gray-500 hover:text-gray-700">Contact Support</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <style>
        .chaport-container {
            bottom: 80px !important;
        }
    </style>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    <!-- Begin of Chaport Live Chat code -->
    <!-- <script type="text/javascript">
        (function (w, d, v3) {
            w.chaportConfig = {
                appId: '68560630c8bc0005dd8796dd'
            };

            if (w.chaport) return; v3 = w.chaport = {}; v3._q = []; v3._l = {}; v3.q = function () { v3._q.push(arguments) }; v3.on = function (e, fn) { if (!v3._l[e]) v3._l[e] = []; v3._l[e].push(fn) }; var s = d.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = 'https://app.chaport.com/javascripts/insert.js'; var ss = d.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss)
        })(window, document);
    </script> -->
    <!-- End of Chaport Live Chat code -->
    <!-- Enhanced Page Loading Animation -->
    <script>
        window.onload = function () {
            const preloader = document.querySelector('.page-loading');

            // Add a slight delay to make loading animation more noticeable
            setTimeout(function () {
                preloader.classList.remove('active');
                setTimeout(function () {
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
