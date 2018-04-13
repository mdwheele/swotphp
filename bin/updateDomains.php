<?php

chdir(dirname(__FILE__)  . DIRECTORY_SEPARATOR);

require '../vendor/autoload.php';

$upstream_dir = '..' . DIRECTORY_SEPARATOR . 'upstream';
$archive_zip = 'leereilly_archive.zip';
$archive_dir = 'leereilly_archive';
$domains_dir = '..' . DIRECTORY_SEPARATOR . 'domains';

// Create upstream dir to hold leereilly/swot files
if (! is_dir($upstream_dir)) {
    echo 'Creating upstream directory' . PHP_EOL;
    mkdir($upstream_dir);
}

// Normalize path
$upstream_dir = realpath($upstream_dir) . DIRECTORY_SEPARATOR;
$archive_dir = $upstream_dir . $archive_dir;

if (! file_exists($upstream_dir . $archive_zip) || time()-filemtime($upstream_dir . $archive_zip) > 43200) {
    echo 'Downloading fresh Ruby Swot archive' . PHP_EOL;
    file_put_contents($upstream_dir . $archive_zip, fopen('https://github.com/leereilly/swot/archive/master.zip', 'r'));
    echo 'Download complete' . PHP_EOL;
}

if (! file_exists($upstream_dir . $archive_zip)) {
    echo 'Archive file not found. ' . $upstream_dir . $archive_zip . PHP_EOL;
    exit(1);
}

echo 'Extracting archive.' . PHP_EOL;
$zip = new ZipArchive;
$res = $zip->open($upstream_dir . $archive_zip);
if ($res !== true) {
    echo 'Failed to open archive. ' . $upstream_dir . $archive_zip . PHP_EOL;
    exit(1);
}
$res = $zip->extractTo($archive_dir);
$zip->close();
if ($res !== true) {
    echo 'Failed to extract to: ' . $archive_dir . PHP_EOL;
    exit(1);
}
echo 'Extracting complete' . PHP_EOL;

$archive_dir = realpath($archive_dir) . DIRECTORY_SEPARATOR;

if (is_dir($domains_dir)) {
    echo 'Cleaning existing domains' . PHP_EOL;
    $dir = new RecursiveDirectoryIterator($domains_dir, RecursiveDirectoryIterator::SKIP_DOTS);
    foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $filename => $file) {
        if (is_file($filename)) {
            unlink($filename);
        } else {
            rmdir($filename);
        }
    }
    rmdir($domains_dir);
}

$archive_domains_dir = $archive_dir . 'swot-master' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'domains';

if (! is_dir($archive_domains_dir)) {
    echo 'Could not find domain direcrory at: ' . $archive_domains_dir . PHP_EOL;
    exit(1);
}

echo 'Updating our domain list.' . PHP_EOL;
mkdir($domains_dir, 0755);
foreach ($iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($archive_domains_dir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
) as $item) {
    if ($item->isDir()) {
        mkdir($domains_dir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    } else {
        copy($item, $domains_dir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    }
}
echo 'Update complete.' . PHP_EOL;
