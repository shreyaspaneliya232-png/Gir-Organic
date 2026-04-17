<?php
require_once __DIR__ . '/config.php';

function clean($value)
{
    if (is_array($value)) {
        return array_map('clean', $value);
    }

    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function flash($key, $message = null)
{
    if ($message === null) {
        if (!empty($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }

    $_SESSION['flash'][$key] = $message;
}

function getTranslations()
{
    static $translations = null;

    if ($translations === null) {
        $translations = require __DIR__ . '/lang.php';
    }

    return $translations;
}

function translate($key)
{
    $translations = getTranslations();
    $lang = $_SESSION['lang'] ?? 'en';

    if (!isset($translations[$lang])) {
        $lang = 'en';
    }

    return $translations[$lang][$key] ?? $key;
}

function setLanguage($lang)
{
    $available = ['en', 'gu'];
    if (in_array($lang, $available, true)) {
        $_SESSION['lang'] = $lang;
    }
}

function getCurrentUser()
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn()
{
    return !empty($_SESSION['user']);
}

function isAdminLoggedIn()
{
    return !empty($_SESSION['admin']);
}
