<?php
$page_title = "Twandikire | Contact";
include 'includes/header.php';
?>

<h1>Twandikire / Contact Us</h1>

<div class="contact-info">
    <h2>Amakuru y'ihutirwa / Contact Information</h2>
    <ul>
        <li><strong>Email:</strong> info@amashuri.rw (cyangwa infofrss8@gmail.com)</li>
        <li><strong>Telephone:</strong> +250 788 792 574 (RSSF)</li>
        <li><strong>Location:</strong> Kigali, Rwanda</li>
    </ul>

    <h2>Twandikire hano / Send us a message</h2>
    <form action="#" method="post">
        <label>Izina / Name:</label><br>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Ubutumwa / Message:</label><br>
        <textarea name="message" rows="6" required></textarea><br><br>
        
        <button type="submit">Ohereza / Send</button>
    </form>

    <p><small>(Iyi form ntiracyakorwa neza – ikoreshe email cyangwa telefone kuri iki gihe.)</small></p>
</div>

<?php include 'includes/footer.php'; ?>