<?php include 'portofoliodata.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>


    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portofolio Anisha</title>

    <link rel="stylesheet" href="../assets/portofoliostyle.css">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

<!-- NAVBAR -->
<nav>

    <div class="logo">Anisha</div>

    <ul>
        <li><a href="?page=home">Home</a></li>
        <li><a href="?page=about">About</a></li>
        <li><a href="?page=skill">Skill</a></li>
        <li><a href="?page=project">Project</a></li>
        <li><a href="?page=contact">Contact</a></li>
    </ul>

</nav>

<?php

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

?>

<!-- HOME -->
<?php if($page == 'home') : ?>

<section class="home">

    <div class="home-img">
        <img src="../assets/foto/guwe4.jpeg">
    </div>

    <div class="home-content">

        <h3>Hello, Myself</h3>

        <h1><?= $nama ?></h1>

        <h3><?= $jurusan ?></h3>

        <p><?= $tentang ?></p>

        <!-- SOCIAL MEDIA -->
        <div class="social-media">

            <!-- INSTAGRAM -->
            <a href="https://instagram.com/anshfbry" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>

            <!-- WHATSAPP -->
            <a href="https://wa.me/6281328283751?text=Halo%20Anisha" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>

            <!-- EMAIL -->
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=bianshfbry@gmail.com" target="_blank">
                <i class="fas fa-envelope"></i>
            </a>

              <!-- GITHUB -->
            <a href="https://github.com/biione" target="_blank">
                <i class="fab fa-github"></i>
             </a>

        </div>

        <a href="#" class="btn">Download CV</a>

    </div>

</section>

<?php endif; ?>
<!-- ABOUT -->
<?php if($page == 'about') : ?>

<section class="about-modern">

    <!-- TEXT -->
    <div class="about-text">

        <h1>About <span>Me</span></h1>

        <h3>I'm a Frontend Developer</h3>

        <p>
            Halo, saya <?= $nama ?> mahasiswa <?= $jurusan ?> 
            yang sedang belajar HTML, CSS, dan PHP. 
            Saya memiliki minat dalam pengembangan website 
            modern dan desain antarmuka yang menarik.
        </p>

        <a href="#" class="about-btn">Read More</a>

    </div>

    <!-- FOTO -->
    <div class="about-photo">

        <img src="../assets/foto/guwe4.jpeg">

    </div>

</section>

<?php endif; ?>

<!-- SKILL -->
<?php if($page == 'skill') : ?>

<section class="page">

    <h1>My Skills</h1>

    <div class="skill-box">

        <?php foreach($skill as $s): ?>

            <div class="card">
                <h3><?= $s ?></h3>
            </div>

        <?php endforeach; ?>

    </div>

</section>

<?php endif; ?>
<!-- PROJECT -->
<?php if($page == 'project') : ?>

<section class="page">

    <h1>My Projects</h1>

    <div class="project-container">

        <?php foreach($projek as $p) : ?>

        <div class="project-box">

            <h3><?= $p['judul']; ?></h3>

            <p><?= $p['deskripsi']; ?></p>

            <a href="<?= $p['link']; ?>" class="project-btn">
                Read
            </a>

        </div>

        <?php endforeach; ?>

    </div>

</section>

<?php endif; ?>

<!-- CONTACT -->
<?php if($page == 'contact') : ?>

<section class="contact-modern">

    <h1>Contact <span>Me</span></h1>

    <form>

        <input type="text" placeholder="Full Name">

        <input type="email" placeholder="Email">

        <input type="text" placeholder="Phone Number">

        <input type="text" placeholder="Subject">

        <textarea placeholder="Your Message"></textarea>

        <button type="submit">Send Message</button>

    </form>

</section>

<?php endif; ?>

</body>
</html>