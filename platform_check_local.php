<?php
// platform_check_local.php

$requiredPhpVersion = '8.2.0';
$requiredExtensions = [
    'ctype',
    'iconv',
    'intl',
    'zip',
    'mbstring'
];

$errors = [];

// Vérification de la version PHP
if (version_compare(PHP_VERSION, $requiredPhpVersion, '<')) {
    $errors[] = sprintf(
        "⚠️ Version PHP insuffisante : %s détectée, %s ou plus requise.",
        PHP_VERSION,
        $requiredPhpVersion
    );
}

// Vérification des extensions
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = sprintf("⚠️ Extension PHP manquante : %s", $ext);
    }
}

if ($errors) {
    foreach ($errors as $error) {
        echo $error . PHP_EOL;
    }
    echo PHP_EOL . "❌ Veuillez installer/activer les extensions manquantes et mettre à jour PHP." . PHP_EOL;
    exit(1);
}

echo "✅ Tous les prérequis PHP sont satisfaits. Version PHP : " . PHP_VERSION . PHP_EOL;
exit(0);
