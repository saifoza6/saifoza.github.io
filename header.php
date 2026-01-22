<?php $pageTitle = $pageTitle ?? 'Media Downloader'; ?>
<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f7f6;
        }
        .main-container { flex-shrink: 0; }
        .card { 
            border: none; 
            border-radius: 1rem; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07); 
            background: #ffffff;
        }
        .navbar-brand { font-weight: 700; color: #333 !important; }
        .nav-link { font-weight: 500; }
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .footer {
            background-color: #f4f7f6;
            color: black;
            padding: 1.5rem 0;
            font-size: 0.9rem;
        }
        .btn-gradient {
            background: var(--primary-gradient);
            border: none; color: white; font-weight: 600;
            box-shadow: 0 7px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover, .btn-gradient:focus {
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="d-flex flex-column h-100">

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-download text-primary me-2"></i>Saiul.Com</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="tiktok.php">TikTok</a></li>
                <li class="nav-item"><a class="nav-link" href="youtube.php">YouTube</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="main-container py-5 flex-shrink-0">
