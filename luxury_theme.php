<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach ($ite as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);

        // Core Layout Backgrounds (Dark Zinc Mode)
        $content = str_replace('bg-gray-50/95', 'bg-zinc-950/95', $content);
        $content = str_replace('bg-gray-50', 'bg-zinc-950', $content);
        $content = str_replace('bg-white/80', 'bg-zinc-950/80', $content);
        $content = str_replace('bg-white', 'bg-zinc-900', $content);
        $content = str_replace('bg-gray-100', 'bg-zinc-900', $content);
        $content = str_replace('bg-gray-800', 'bg-zinc-800', $content);
        $content = str_replace('bg-gray-900', 'bg-zinc-900', $content);

        // Translucent backgrounds
        $content = str_replace('bg-gray-200', 'bg-zinc-800', $content);
        
        // Borders
        $content = str_replace('border-gray-100', 'border-zinc-800', $content);
        $content = str_replace('border-gray-200', 'border-zinc-800/80', $content);
        $content = str_replace('border-gray-300', 'border-zinc-700', $content);

        // Text Colors
        $content = str_replace('text-gray-500', 'text-zinc-400', $content);
        $content = str_replace('text-gray-600', 'text-zinc-400', $content); // slightly lighter for dark mode
        $content = preg_replace('/text-gray-900(?!0)/', 'text-zinc-100', $content);
        $content = str_replace('text-gray-800', 'text-zinc-200', $content);

        // Shapes & Minimalism (Remove excessive rounded corners)
        $content = preg_replace('/rounded-2xl/', 'rounded-sm', $content);
        $content = preg_replace('/rounded-3xl/', 'rounded-sm', $content);
        $content = preg_replace('/rounded-xl/', 'rounded-sm', $content);
        $content = preg_replace('/rounded-lg/', 'rounded-sm', $content);

        // Shadows
        $content = preg_replace('/shadow-2xl shadow-gray-200\/50/', 'shadow-2xl shadow-black/80', $content);
        $content = preg_replace('/shadow-lg/', 'shadow-sm', $content);
        
        // Luxury Accents & Buttons 
        // Replace gradients with subtle luxury outlines
        $content = preg_replace('/bg-gradient-to-r from-[a-z]+-\d+ to-[a-z]+-\d+ hover:from-[a-z]+-\d+ hover:to-[a-z]+-\d+ text-white font-bold text-sm shadow-sm hover:-translate-y-0.5 transition-all/', 'border border-[#C5A880]/50 text-[#C5A880] text-xs uppercase tracking-widest hover:bg-[#C5A880]/5 transition-all duration-700', $content);
        $content = preg_replace('/bg-gradient-to-r from-[a-z]+-\d+ to-[a-z]+-\d+ hover:from-[a-z]+-\d+ hover:to-[a-z]+-\d+ text-white text-sm font-semibold shadow-sm hover:-translate-y-0.5 transition-all/', 'border border-[#C5A880]/50 text-[#C5A880] text-xs uppercase tracking-widest hover:bg-[#C5A880]/5 transition-all duration-700', $content);
        
        $content = preg_replace('/bg-gradient-to-r from-[a-z]+-600 to-[a-z]+-600 hover:from-[a-z]+-500 hover:to-[a-z]+-500 text-white font-semibold shadow-sm transition-all/', 'border border-[#C5A880]/50 text-[#C5A880] text-xs uppercase tracking-widest hover:bg-[#C5A880]/5 transition-all duration-700', $content);
        
        // Let's just catch the main buttons with str_replace on common patterns
        $content = str_replace('bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm', 'border border-[#C5A880]/60 text-[#C5A880] text-xs uppercase tracking-[0.2em] hover:bg-[#C5A880]/10', $content);
        $content = str_replace('bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-sm font-semibold', 'border border-[#C5A880]/60 text-[#C5A880] text-xs uppercase tracking-[0.2em] hover:bg-[#C5A880]/10', $content);
        $content = str_replace('bg-gradient-to-r from-blue-600 to-indigo-600 text-zinc-100 text-sm font-semibold', 'border border-[#C5A880]/60 text-[#C5A880] hover:bg-[#C5A880]/10', $content);

        // Logo
        $content = str_replace('text-blue-500', 'text-[#C5A880]', $content);
        $content = str_replace('bg-gradient-to-r from-zinc-100 to-zinc-400', 'text-zinc-100 font-serif font-normal', $content);
        $content = preg_replace('/bg-gradient-to-br from-blue-500 to-indigo-500/', 'border border-zinc-700 bg-zinc-900', $content);
        
        // Gradient texts to champagne gold
        $content = preg_replace('/bg-gradient-to-r from-[-a-z0-9]+ to-[-a-z0-9]+ bg-clip-text text-transparent/', 'text-[#C5A880] font-serif font-light italic', $content);
        $content = preg_replace('/bg-gradient-to-r from-[a-z]+-400 to-[a-z]+-400 bg-clip-text text-transparent/', 'text-[#C5A880] font-serif font-light italic', $content);

        // Headings font to serif
        $content = preg_replace('/font-extrabold/', 'font-serif font-light tracking-wide', $content);
        $content = preg_replace('/font-bold/', 'font-medium', $content); // Mute bold
        
        // Remove background gradients
        $content = preg_replace('/bg-gradient-to-l from-blue-900\/20 to-transparent/', '', $content);
        $content = preg_replace('/bg-blue-600\/10 rounded-sm blur-\[100px\] animate-pulse/', '', $content);
        $content = preg_replace('/bg-gradient-to-tr from-blue-600 to-indigo-600/', '', $content);

        file_put_contents($path, $content);
    }
}

echo "Luxury theme applied!";
