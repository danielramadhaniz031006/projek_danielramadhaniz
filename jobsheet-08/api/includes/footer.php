<?php

/* =========================================================
   ELRAM TRAINING CAMP
   FOOTER UTAMA
   ========================================================= */

// Pastikan variabel base tersedia
if (!isset($base)) {

    $script_path = str_replace(
        '\\',
        '/',
        parse_url($_SERVER['SCRIPT_NAME'] ?? '/', PHP_URL_PATH)
    );

    if (
        preg_match(
            '#^(.*?)/api(?:/|$)#',
            $script_path,
            $matches
        )
    ) {
        $base = rtrim($matches[1], '/');

    } else {

        $base = rtrim(
            str_replace(
                '\\',
                '/',
                dirname($script_path)
            ),
            '/'
        );
    }

    if ($base === '/' || $base === '.') {
        $base = '';
    }
}


// URL JavaScript utama
$app_js = $base . '/assets/js/app.js';

?>

    </main>


    <!-- =====================================================
         FOOTER
         ===================================================== -->

    <footer>

        <p>
            &copy; <?php echo date('Y'); ?>
            ELRAM Training Camp.
            All Rights Reserved.
        </p>

    </footer>


    <!-- =====================================================
         JAVASCRIPT UTAMA
         ===================================================== -->

    <script
        src="<?php echo htmlspecialchars($app_js); ?>"
        defer
    ></script>


    <!-- =====================================================
         JAVASCRIPT TAMBAHAN JIKA DIPERLUKAN
         ===================================================== -->

    <?php if (!empty($extra_scripts) && is_array($extra_scripts)): ?>

        <?php foreach ($extra_scripts as $src): ?>

            <script
                src="<?php echo htmlspecialchars($src); ?>"
                defer
            ></script>

        <?php endforeach; ?>

    <?php endif; ?>

</body>
</html>