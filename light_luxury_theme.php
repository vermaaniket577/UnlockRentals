<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach ($ite as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);

        // Backgrounds: Switch back from dark zinc to pristine white/stone
        $content = str_replace('bg-zinc-950/95', 'bg-white/95', $content);
        $content = str_replace('bg-zinc-950/80', 'bg-white/80', $content);
        $content = preg_replace('/bg-zinc-950(?!0)/', 'bg-white', $content);
        $content = str_replace('bg-zinc-900', 'bg-stone-50', $content);
        $content = str_replace('bg-zinc-800', 'bg-stone-100', $content);
        
        // Borders
        $content = str_replace('border-zinc-800/80', 'border-stone-200/50', $content);
        $content = str_replace('border-zinc-800', 'border-stone-200/50', $content);
        $content = str_replace('border-zinc-700', 'border-stone-200', $content);

        // Texts: Darken text for light backgrounds
        $content = str_replace('text-zinc-100', 'text-zinc-900', $content);
        $content = str_replace('text-zinc-200', 'text-zinc-800', $content);
        $content = str_replace('text-zinc-300', 'text-zinc-800', $content);
        $content = str_replace('text-zinc-400', 'text-zinc-500', $content);
        $content = str_replace('text-white', 'text-zinc-900', $content);

        // Subtle gradient adjustments
        $content = str_replace('from-zinc-950', 'from-white', $content);
        $content = str_replace('via-zinc-950', 'via-white', $content);
        $content = str_replace('to-zinc-950', 'to-white', $content);

        // Ensure buttons and icons remain elegant
        // Example: The avatar initials have text-zinc-900 now, which is correct on dark or light depending on its internal bg.
        
        // Adjust spacing for "Massive padding and margins" (mainly in hero section)
        $content = str_replace('py-20 lg:py-32', 'py-24 lg:py-40', $content);
        $content = str_replace('pt-24 pb-20 lg:pt-32 lg:pb-28', 'pt-32 pb-24 lg:pt-48 lg:pb-36', $content);
        
        // Hero background image reference update
        $content = str_replace('hero_apartment.png', 'luxury_sunlit.png', $content);

        // Maintain selection style but with a slight cream tint
        $content = str_replace('selection:text-zinc-50', 'selection:text-zinc-900', $content);

        file_put_contents($path, $content);
    }
}

echo "Light Luxury theme applied!";
