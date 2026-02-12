<?php
session_start();
$session_timeout = isset($session_timeout) && is_int($session_timeout) ? $session_timeout : 1800;

if (isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY'] > $session_timeout)) {
    session_unset();
    session_destroy();
    header("Location: ../login.php?error=Session timeout, silakan login kembali");
    exit();
} 

$_SESSION['LAST_ACTIVITY'] = time();


if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}


function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}


function checkRole($allowedRoles) {
    if (!isLoggedIn() || !isset($_SESSION['role'])) {
        return false;
    }
    
    if (is_array($allowedRoles)) {
        return in_array($_SESSION['role'], $allowedRoles);
    }
    
    return $_SESSION['role'] === $allowedRoles;
}


function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}


function redirectBasedOnRole() {
    if (isLoggedIn()) {
        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: admin/index.php");
                break;
            case 'assessor':
                header("Location: assessor/index.php");
                break;
            case 'user':
                header("Location: user/index.php");
                break;
            default:
                header("Location: dashboard.php");
        }
        exit();
    }
}
?>