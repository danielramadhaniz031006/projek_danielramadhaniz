</main>

<footer>

    <p>&copy; ELRAM Training Camp</p>

</footer>

<?php

// Memuat JavaScript utama
$app_js = $base . '/assets/js/app.js';

?>

<script src="<?php echo htmlspecialchars($app_js); ?>"></script>

<?php if (!empty($extra_scripts)): ?>

    <?php foreach ($extra_scripts as $src): ?>

        <script src="<?php echo htmlspecialchars($src); ?>"></script>

    <?php endforeach; ?>

<?php endif; ?>

</body>
</html>