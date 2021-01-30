<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony:risky' => true,
        '@PHP71Migration:risky' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_after_namespace' => true,
        'single_blank_line_before_namespace' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude([
            'dist',
            'node_modules',
            'vendor',
        ])
        ->in(__DIR__)
    );
