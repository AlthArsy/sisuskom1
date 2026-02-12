<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <style>
            * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2c3e50, #1a2530);
            color: white;
            padding: 20px 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        
        .logo h1 {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo span {
            color: #3498db;
        }
        
        .nav-menu {
            list-style: none;
        }
        
        .nav-menu li {
            padding: 15px 25px;
            transition: background 0.3s;
        }
        
        .nav-menu li:hover {
            background-color: #34495e;
        }
        
        .nav-menu li.active {
            background-color: #3498db;
            border-left: 4px solid #2980b9;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Header */
        .header {
            background-color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            z-index: 10;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            background-color: #f5f7fa;
            border-radius: 30px;
            padding: 10px 20px;
            width: 400px;
        }
        
        .search-bar input {
            border: none;
            background: transparent;
            margin-left: 10px;
            width: 100%;
            outline: none;
            font-size: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: white;
        }
        
        .user-details h3 {
            font-size: 1rem;
            margin-bottom: 3px;
        }
        
        .user-details p {
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        /* Content Area */
        .content {
            padding: 30px;
            flex: 1;
        }
        
        .welcome-section {
            margin-bottom: 30px;
        }
        
        .welcome-section h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .welcome-section p {
            color: #7f8c8d;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        
        .card-1 .card-icon {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        
        .card-2 .card-icon {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }
        
        .card-3 .card-icon {
            background-color: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
        }
        
        .card-4 .card-icon {
            background-color: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }
        
        .card h3 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .card p {
            color: #7f8c8d;
        }
        
        .recent-activities {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #f1f2f6;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .activity-1 .activity-icon {
            background-color: #3498db;
        }
        
        .activity-2 .activity-icon {
            background-color: #2ecc71;
        }
        
        .activity-3 .activity-icon {
            background-color: #9b59b6;
        }
        
        .activity-4 .activity-icon {
            background-color: #f1c40f;
        }
        
        .activity-details h4 {
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .activity-details p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .activity-time {
            margin-left: auto;
            color: #95a5a6;
            font-size: 0.85rem;
        }
        
        /* Footer */
        .footer {
            background-color: white;
            padding: 20px 30px;
            text-align: center;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            font-size: 0.9rem;
        }
        

    </style>
</head>
<body>
    <form action="-">
        <div class="container">
            <!-- Sidebar Navigasi -->
            <aside class="sidebar">
                <div class="logo">
                    <h1><i class="fas fa-chart-line"></i> <span>Dashboard</span></h1>
                </div>
                
                <ul class="nav-menu">
                    <li class="active">
                        <a href="#"><i class="fas fa-home"></i> <span>Dashboard</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-users"></i> <span>Pengguna</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-cog"></i> <span>Pengaturan</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-question-circle"></i> <span>Bantuan</span></a>
                    </li>
                </ul>
            </aside>
            
            <!-- Konten Utama -->
            <main class="main-content">
                <!-- Header -->
                <header class="header">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari pengguna, laporan, atau dokumen...">
                    </div>
                    
                    <div class="user-info">
                        <div class="notifications">
                            <i class="fas fa-bell fa-lg"></i>
                            <span class="notification-badge"></span>
                        </div>
                        
                        <div class="user-profile">
                            <div class="avatar">ASS</div>
                            <div class="user-details">
                                <h3>Assesi</h3>
                                <p>Administrator</p>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </header>
                
                <!-- Area Konten -->
                <section class="content">
                    <div class="welcome-section">
                        <h2>Selamat Datang!</h2>
                    </div>
                    </section>
                <!-- Footer -->
                <footer class="footer">
                    <p>&copy; 2023</p>
                </footer>
            </main>
        </div>
    
    </form>
</body>
</html>