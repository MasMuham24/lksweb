<?php
session_start();

$file = "data/messages.json";
$messages = [];

// Pastikan folder data ada
if (!file_exists('data')) {
    mkdir('data', 0777, true);
}

if (file_exists($file)) {
    $messages = json_decode(file_get_contents($file), true);
    if ($messages === null) {
        $messages = [];
    }
}

/* ===== HAPUS DATA ===== */
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    
    if (isset($messages[$index])) {
        unset($messages[$index]);
        $messages = array_values($messages); // reset index
        
        if (file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT))) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Pesan berhasil dihapus!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Gagal menghapus pesan. Cek permission folder!';
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Pesan tidak ditemukan!';
    }
    
    header("Location: dashboard.php");
    exit;
}

/* ===== UPDATE DATA ===== */
if (isset($_POST['update'])) {
    $index = (int)$_POST['index'];
    
    if (isset($messages[$index])) {
        $messages[$index]['nama']  = trim($_POST['nama']);
        $messages[$index]['email'] = trim($_POST['email']);
        $messages[$index]['phone'] = trim($_POST['phone']);
        $messages[$index]['kota']  = trim($_POST['kota']);
        $messages[$index]['pesan'] = trim($_POST['pesan']);

        if (file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT))) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Pesan berhasil diupdate!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Gagal mengupdate pesan. Cek permission folder!';
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Data tidak valid!';
    }
    
    header("Location: dashboard.php");
    exit;
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pesan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #8b5cf6;
            --success: #10b981;
            --success-dark: #059669;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --warning: #f59e0b;
            --dark: #1f2937;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: backgroundShift 15s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes backgroundShift {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 45px;
            box-shadow: var(--shadow-xl);
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 45px;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
            animation: expandLine 1s ease-out;
        }

        @keyframes expandLine {
            from { width: 0; }
            to { width: 80px; }
        }

        h2 {
            color: var(--gray-800);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeIn 0.8s ease-out;
        }

        .subtitle {
            color: var(--gray-600);
            font-size: 15px;
            font-weight: 400;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 25px;
            border-radius: 16px;
            color: white;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Form Styling */
        .edit-form {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
            padding: 35px;
            border-radius: 20px;
            margin-bottom: 35px;
            border: 2px solid var(--primary-light);
            box-shadow: var(--shadow-md);
            animation: scaleIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .edit-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.1), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        h3 {
            color: var(--primary-dark);
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray-700);
            font-weight: 600;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button,
        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        button::before,
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        button:hover::before,
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow);
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        button[type="submit"]:active {
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
        }

        .btn-cancel:hover {
            background: var(--gray-200);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* Table Styling */
        .table-container {
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 5px;
        }

        .table-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-800);
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 40px 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 250px;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            background: white;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        th {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 18px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        th:first-child {
            border-top-left-radius: 16px;
        }

        th:last-child {
            border-top-right-radius: 16px;
        }

        tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: rowFadeIn 0.5s ease-out backwards;
        }

        tbody tr:nth-child(1) { animation-delay: 0.1s; }
        tbody tr:nth-child(2) { animation-delay: 0.15s; }
        tbody tr:nth-child(3) { animation-delay: 0.2s; }
        tbody tr:nth-child(4) { animation-delay: 0.25s; }
        tbody tr:nth-child(5) { animation-delay: 0.3s; }

        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        tbody tr:hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.05), transparent);
            transform: translateX(5px);
            box-shadow: inset 4px 0 0 var(--primary);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
            font-size: 14px;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-edit,
        .btn-del {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--success), var(--success-dark));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-edit:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .btn-del {
            background: linear-gradient(135deg, var(--danger), var(--danger-dark));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-del:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-600);
            font-size: 16px;
        }

        .no-data-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: var(--primary-light);
            color: white;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 25px;
                border-radius: 16px;
            }

            h2 {
                font-size: 28px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .table-wrapper {
                border-radius: 12px;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .search-box input {
                width: 100%;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
        }

        /* Alert Success */
        .alert-success {
            background: linear-gradient(135deg, var(--success), var(--success-dark));
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 600;
            box-shadow: var(--shadow-md);
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            min-width: 350px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            z-index: 9999;
            animation: toastSlideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            overflow: hidden;
        }

        @keyframes toastSlideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toastSlideOut {
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-notification.hide {
            animation: toastSlideOut 0.3s ease-in forwards;
        }

        .toast-notification.success {
            border-left: 5px solid var(--success);
        }

        .toast-notification.error {
            border-left: 5px solid var(--danger);
        }

        .toast-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: iconPop 0.5s ease-out 0.2s backwards;
        }

        @keyframes iconPop {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .toast-notification.success .toast-icon {
            background: linear-gradient(135deg, var(--success), var(--success-dark));
            color: white;
        }

        .toast-notification.error .toast-icon {
            background: linear-gradient(135deg, var(--danger), var(--danger-dark));
            color: white;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
            color: var(--gray-800);
        }

        .toast-message {
            font-size: 14px;
            color: var(--gray-600);
        }

        .toast-close {
            flex-shrink: 0;
            width: 30px;
            height: 30px;
            border: none;
            background: var(--gray-100);
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            transition: all 0.3s ease;
        }

        .toast-close:hover {
            background: var(--gray-200);
            transform: rotate(90deg);
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 0 0 0 16px;
            animation: progressBar 5s linear forwards;
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        /* Responsive Toast */
        @media (max-width: 768px) {
            .toast-notification {
                top: 20px;
                right: 20px;
                left: 20px;
                min-width: auto;
            }
        }

        /* Custom Modal Confirmation */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 35px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: modalSlideUp 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
        }

        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            animation: iconBounce 0.6s ease-out 0.3s backwards;
        }

        @keyframes iconBounce {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .modal-icon svg {
            width: 45px;
            height: 45px;
            color: var(--danger);
            animation: iconShake 0.5s ease-out 0.5s;
        }

        @keyframes iconShake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-800);
            text-align: center;
            margin-bottom: 12px;
        }

        .modal-message {
            font-size: 15px;
            color: var(--gray-600);
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .modal-highlight {
            font-weight: 700;
            color: var(--danger);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            padding: 2px 8px;
            border-radius: 6px;
        }

        .modal-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            padding: 12px 16px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid var(--warning);
        }

        .modal-warning-text {
            font-size: 13px;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .modal-btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .modal-btn-cancel {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
        }

        .modal-btn-cancel:hover {
            background: var(--gray-200);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .modal-btn-delete {
            background: linear-gradient(135deg, var(--danger), var(--danger-dark));
            color: white;
            box-shadow: var(--shadow);
        }

        .modal-btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .modal-btn-delete:active,
        .modal-btn-cancel:active {
            transform: translateY(0);
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal-content {
                padding: 25px;
            }

            .modal-title {
                font-size: 20px;
            }

            .modal-actions {
                flex-direction: column-reverse;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>📬 Dashboard Pesan Masuk</h2>
            <p class="subtitle">Kelola semua pesan pelanggan Anda dengan mudah</p>
        </div>

        <!-- Toast Notification -->
        <?php if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
            <div class="toast-notification <?= $_SESSION['alert_type'] ?>" id="toastNotification">
                <div class="toast-icon">
                    <?php if ($_SESSION['alert_type'] === 'success'): ?>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    <?php else: ?>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="toast-content">
                    <div class="toast-title">
                        <?= $_SESSION['alert_type'] === 'success' ? 'Berhasil!' : 'Gagal!' ?>
                    </div>
                    <div class="toast-message"><?= $_SESSION['alert_message'] ?></div>
                </div>
                <button class="toast-close" onclick="closeToast()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="toast-progress"></div>
            </div>
            <?php 
                unset($_SESSION['alert_type']);
                unset($_SESSION['alert_message']);
            ?>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?= count($messages) ?></div>
                <div class="stat-label">Total Pesan</div>
            </div>
        </div>

        <?php if (isset($_GET['edit'])):
            $i = $_GET['edit'];
            $msg = $messages[$i];
        ?>
            <div class="edit-form">
                <h3>✏️ Edit Pesan</h3>
                <form method="post">
                    <input type="hidden" name="index" value="<?= $i ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($msg['nama']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($msg['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>No. Handphone</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($msg['phone']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Kota</label>
                            <input type="text" name="kota" value="<?= htmlspecialchars($msg['kota']) ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea name="pesan" required><?= htmlspecialchars($msg['pesan']) ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="update">
                            <span>💾</span>
                            <span>Update Pesan</span>
                        </button>
                        <a href="dashboard.php" class="btn btn-cancel">
                            <span>✖</span>
                            <span>Batal</span>
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-header">
                <div class="table-title">📋 Daftar Pesan</div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Kota</th>
                            <th>Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messages)): ?>
                            <tr>
                                <td colspan="7" class="no-data">
                                    <div class="no-data-icon">📭</div>
                                    <div>Belum ada pesan masuk</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($messages as $i => $msg): ?>
                                <tr>
                                    <td><strong><?= $i + 1 ?></strong></td>
                                    <td><?= htmlspecialchars($msg['nama']) ?></td>
                                    <td><?= htmlspecialchars($msg['email']) ?></td>
                                    <td><?= htmlspecialchars($msg['phone']) ?></td>
                                    <td><?= htmlspecialchars($msg['kota']) ?></td>
                                    <td><?= htmlspecialchars($msg['pesan']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a class="btn-edit" href="?edit=<?= $i ?>">
                                                <span>✏️</span>
                                                <span>Edit</span>
                                            </a>
                                            <a class="btn-del" href="?delete=<?= $i ?>" onclick="return confirmDelete(event, '<?= htmlspecialchars($msg['nama'], ENT_QUOTES) ?>')">
                                                <span>🗑️</span>
                                                <span>Hapus</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <p class="modal-message">
                Apakah Anda yakin ingin menghapus pesan dari<br>
                <span class="modal-highlight" id="deleteName"></span>?
            </p>
            <div class="modal-warning">
                <div class="modal-warning-text">
                    <span>⚠️</span>
                    <span>Data yang dihapus tidak dapat dikembalikan!</span>
                </div>
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">
                    <span>✖</span>
                    <span>Batal</span>
                </button>
                <button class="modal-btn modal-btn-delete" id="confirmDeleteBtn">
                    <span>🗑️</span>
                    <span>Ya, Hapus</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteUrl = '';

        // Auto close toast after 5 seconds
        const toast = document.getElementById('toastNotification');
        if (toast) {
            setTimeout(() => {
                closeToast();
            }, 5000);
        }

        function closeToast() {
            const toast = document.getElementById('toastNotification');
            if (toast) {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Modal Delete Functions
        function showDeleteModal(url, nama) {
            deleteUrl = url;
            document.getElementById('deleteName').textContent = nama;
            document.getElementById('deleteModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            deleteUrl = '';
        }

        // Confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteUrl) {
                this.innerHTML = '<span class="loading"></span><span>Menghapus...</span>';
                this.disabled = true;
                
                setTimeout(() => {
                    window.location.href = deleteUrl;
                }, 500);
            }
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Konfirmasi hapus dengan modal
        function confirmDelete(event, nama) {
            event.preventDefault();
            const url = event.currentTarget.href;
            showDeleteModal(url, nama);
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading state to buttons (Fixed)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && this.checkValidity()) {
                    submitBtn.innerHTML = '<span class="loading"></span><span>Menyimpan...</span>';
                    // Don't disable to allow form submission
                }
            });
        });

        // Table row click animation
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('.action-buttons')) {
                    this.style.animation = 'none';
                    setTimeout(() => {
                        this.style.animation = 'rowFadeIn 0.3s ease-out';
                    }, 10);
                }
            });
        });
    </script>
</body>

</html>