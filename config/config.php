<?php

// Basic site configuration
const DB_HOST = 'localhost';
const DB_NAME = 'gir_organic';
const DB_USER = 'root';
const DB_PASS = '';
const SITE_TITLE = 'Gir Organic';
const WHATSAPP_NUMBER = '918780982283';
const IMAGE_UPLOAD_DIR = __DIR__ . '/../images/';
const IMAGE_UPLOAD_PATH = 'images/';
const MAX_UPLOAD_SIZE = 2 * 1024 * 1024; // 2MB

// Ensure a secure session environment
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
