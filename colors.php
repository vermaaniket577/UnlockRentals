<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach ($ite as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);

        // Replace 'violet' with 'blue'
        $content = str_replace('violet-100', 'blue-100', $content);
        $content = str_replace('violet-300', 'blue-300', $content);
        $content = str_replace('violet-400', 'blue-500', $content); // deeper contrast
        $content = str_replace('violet-500', 'blue-600', $content);
        $content = str_replace('violet-600', 'blue-700', $content);
        $content = str_replace('violet-900', 'blue-900', $content);
        $content = str_replace('shadow-violet', 'shadow-blue', $content);
        
        // Replace 'fuchsia' with 'indigo'
        $content = str_replace('fuchsia-100', 'indigo-100', $content);
        $content = str_replace('fuchsia-300', 'indigo-300', $content);
        $content = str_replace('fuchsia-400', 'indigo-500', $content); // deeper contrast
        $content = str_replace('fuchsia-500', 'indigo-600', $content);
        $content = str_replace('fuchsia-600', 'indigo-700', $content);
        $content = str_replace('fuchsia-900', 'indigo-900', $content);
        $content = str_replace('shadow-fuchsia', 'shadow-indigo', $content);

        // specific words
        $content = str_replace('from-violet', 'from-blue', $content);
        $content = str_replace('to-fuchsia', 'to-indigo', $content);
        $content = str_replace('hover:from-violet', 'hover:from-blue', $content);
        $content = str_replace('hover:to-fuchsia', 'hover:to-indigo', $content);
        $content = str_replace('via-fuchsia', 'via-indigo', $content);
        
        file_put_contents($path, $content);
    }
}

echo "Professional blue/indigo palette applied!";
