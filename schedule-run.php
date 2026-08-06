<?php

/**
 * Wrapper for Cloudways sin PHP-cron, som kun godtar ETT .php-skript uten
 * argumenter. Denne kjører Laravels planlegger (artisan schedule:run) ved å
 * sette argv og laste artisan.
 *
 * Filen ligger i Laravel-roten (public_html), altså UTENFOR webroot
 * (public_html/public), og er derfor ikke tilgjengelig fra nettet.
 *
 * Cloudways-cron: Type = PHP, Command = schedule-run.php, tid = * * * * *
 */
$_SERVER['argv'] = ['artisan', 'schedule:run'];
$_SERVER['argc'] = 2;
$argv = $_SERVER['argv'];
$argc = 2;

require __DIR__.'/artisan';
