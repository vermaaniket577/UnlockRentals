<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach ($ite as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);

        // Core Layout Backgrounds
        $content = str_replace('bg-gray-950', 'bg-gray-50', $content);
        $content = str_replace('bg-gray-900/50', 'bg-white', $content);
        $content = str_replace('bg-gray-900/60', 'bg-white', $content);
        $content = str_replace('bg-gray-900', 'bg-white', $content);
        
        // Translucent backgrounds
        $content = preg_replace('/bg-white\/5(?!0)/', 'bg-gray-100', $content); // prevent hitting /50
        $content = str_replace('bg-white/10', 'bg-gray-200', $content);
        
        // Borders
        $content = str_replace('border-white/5', 'border-gray-200', $content);
        $content = str_replace('border-white/10', 'border-gray-200', $content);
        $content = str_replace('border-white/20', 'border-gray-300', $content);

        // Text Colors
        $content = str_replace('text-gray-400', 'text-gray-600', $content);
        $content = str_replace('text-white', 'text-gray-900', $content);
        
        // Restore Text White in specific contexts (buttons & labels with backgrounds)
        $content = str_replace('to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500 text-gray-900', 'to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500 text-white', $content);
        $content = str_replace('to-fuchsia-600 text-gray-900', 'to-fuchsia-600 text-white', $content);
        $content = preg_replace('/(bg-[a-z]+-500\/10[^>]*?text-gray-900)/', '$1', $content); // Wait, badges were bg-violet-500/10 text-violet-400 initially.
        
        // specific button text
        $content = str_replace('px-1.5 py-0.5 bg-violet-500 text-gray-900', 'px-1.5 py-0.5 bg-violet-500 text-white', $content);
        $content = str_replace('bg-red-500 px-2 py-1 rounded-lg text-gray-900', 'bg-red-500 px-2 py-1 rounded-lg text-white', $content);
        $content = str_replace('items-center justify-center text-gray-900 text-sm font-bold', 'items-center justify-center text-white text-sm font-bold', $content); // Avatar initials
        $content = str_replace('text-gray-900 font-semibold rounded', 'text-white font-semibold rounded', $content);
        
        // Special navbar overrides
        $content = str_replace('bg-gray-50/80', 'bg-white/80', $content);
        
        // Other specific overrides
        $content = str_replace('shadow-violet-900/20', 'shadow-gray-200/50', $content);
        $content = str_replace('shadow-violet-900/50', 'shadow-gray-300/50', $content);
        $content = str_replace('shadow-fuchsia-900/20', 'shadow-gray-200/50', $content);
        $content = str_replace('border-gray-950', 'border-white', $content); // Avatar overlaps border
        $content = str_replace('from-gray-950', 'from-gray-50', $content); // Gradients
        $content = str_replace('via-gray-950', 'via-gray-50', $content); // Gradients
        $content = str_replace('backdrop-blur-xl border border-gray-200', 'backdrop-blur-xl border border-gray-300', $content); 

        file_put_contents($path, $content);
    }
}

echo "Light theme applied!";
