<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileMeUp</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header class="header-box">
        <nav>
            <div class="nav-left">
                <a href="index.php"><button class="mainPage">Main</button></a>
            </div>
            <div class="nav-right">
                <?php if (isset($_SESSION['userId'])): ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a href="myfiles.php"><button>My Files</button></a>
                    <a href="../php/logout.php"><button>Logout</button></a>
                <?php else: ?>
                    <a href="login.html"><button>Log In</button></a>
                    <a href="signup.html"><button>Sign Up</button></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main>
        <h2 class="welcome-text">Get started. Upload a file!</h2>
        <form action="../php/preview.php" method="post" enctype="multipart/form-data">
            <label for="file">Choose a file:</label>
            <input type="file" id="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
    </main>

    <footer>
        <div class="footer-left">
            <img src="../images/logo.png" alt="Company Logo" class="footer-logo">
        </div>
        <div class="footer-center">
            <p class="copyright">&copy; 2024 FileMeUp. All rights reserved.</p>
        </div>
        <div class="footer-right">
            <a href="https://github.com/KaloyanYonov">Kaloyan Yonov</a>
            <a href="https://github.com/Backpulver">Yoan Hristov</a>
        </div>
    </footer>
</body>

</html>