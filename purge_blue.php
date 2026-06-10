<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach ($ite as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);

        // 1. Destroying Blue Gradients & Backgrounds
        $content = preg_replace('/bg-gradient-to-r from-blue-[0-9]+ to-indigo-[0-9]+ hover:from-blue-[0-9]+ hover:to-indigo-[0-9]+/', 'bg-[#C5A880] hover:bg-[#B3966D]', $content);
        $content = preg_replace('/bg-gradient-to-r from-blue-[0-9]+ to-indigo-[0-9]+/', 'bg-[#C5A880]', $content);
        $content = preg_replace('/bg-gradient-to-br from-blue-[0-9]+ to-indigo-[0-9]+/', 'bg-[#C5A880]', $content);
        $content = preg_replace('/from-blue-[0-9]+\/[0-9]+ to-indigo-[0-9]+\/[0-9]+/', 'from-[#C5A880]/10 to-[#C5A880]/10', $content);
        $content = preg_replace('/bg-blue-[0-9]+\/[0-9]+/', 'bg-[#C5A880]/10', $content);
        $content = preg_replace('/bg-blue-[0-9]+/', 'bg-[#C5A880]', $content);

        // 2. Shadows
        $content = preg_replace('/shadow-blue-[0-9]+\/[0-9]+/', 'shadow-[#C5A880]/20', $content);

        // 3. Texts
        $content = preg_replace('/text-blue-[0-9]+/', 'text-[#C5A880]', $content);

        // 4. Borders
        $content = preg_replace('/border-blue-[0-9]+\/?[0-9]*/', 'border-[#C5A880]/50', $content);

        // 5. Focus rings
        $content = preg_replace('/focus:ring-blue-[0-9]+\/?[0-9]*/', 'focus:ring-[#C5A880]/30', $content);
        $content = preg_replace('/focus:border-blue-[0-9]+\/?[0-9]*/', 'focus:border-[#C5A880]/50', $content);

        // 6. Navigation Logo Specifically
        $content = str_replace('text-blue-500', 'text-[#C5A880]', $content);
        $content = str_replace('text-violet-500', 'text-[#C5A880]', $content);
        
        // 7. Headings consistency (Properties page typography)
        $content = str_replace('font-bold', 'font-serif font-light tracking-wide', $content);
        $content = str_replace('font-extrabold', 'font-serif font-light tracking-wide', $content);

        file_put_contents($path, $content);
    }
}

echo "Blue styling purged and converted to Champagne Light Luxury.";
