<?php
header('Content-Type: text/plain');
echo "=== Git Script Execution ===\n\n";

chdir(__DIR__ . '/..');
echo "Current directory: " . getcwd() . "\n\n";

function runCmd($cmd) {
    echo "Executing: $cmd\n";
    $output = [];
    $ret = 0;
    exec($cmd . ' 2>&1', $output, $ret);
    echo "Exit Code: $ret\n";
    echo "Output:\n" . implode("\n", $output) . "\n";
    echo "----------------------------------------\n\n";
    return $ret;
}

runCmd('git status');
runCmd('git add .');
runCmd('git commit -m "feat: migrate and seed locations database, bind cascading selectors dynamically"');
runCmd('git push');
