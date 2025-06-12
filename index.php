<?php
// Archivo para forzar detección de PHP en Render
// Redirige al index.php de Laravel en public/
header('Location: public/index.php');
exit(); 