<?php
declare(strict_types=1);

// This is the sole allowlist. Never accept a filesystem path from a visitor.
function catalog(): array
{
    return [
        'exam-ready-systems' => ['file' => 'EXAM_READY_SYSTEMS (2).zip', 'title' => 'EXAM_READY_SYSTEMS', 'description' => 'Download the complete EXAM_READY_SYSTEMS ZIP package.', 'label' => 'EXAM RESOURCES', 'version' => null],
        'starter' => ['file' => 'purple-starter.zip', 'title' => 'Purple starter', 'description' => 'A responsive HTML starter with a purple theme, navigation, and feature cards.', 'label' => 'START BUILDING', 'version' => '1.0'],
        'components' => ['file' => 'component-recipes.zip', 'title' => 'Component recipes', 'description' => 'Copy-ready Bootstrap pricing, alert, and contact form layouts for your next project.', 'label' => 'EXPLORE COMPONENTS', 'version' => '1.0'],
    ];
}

function archive_path(string $filename): ?string
{
    $root = realpath(__DIR__ . '/../downloads');
    $path = realpath(__DIR__ . '/../downloads/' . $filename);
    if ($root === false || $path === false || dirname($path) !== $root || strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'zip' || !is_file($path) || !is_readable($path)) {
        return null;
    }
    return $path;
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function file_size_label(int $bytes): string
{
    if ($bytes >= 1024 * 1024) {
        return number_format($bytes / (1024 * 1024), 2) . ' MB';
    }
    return $bytes < 1024 ? $bytes . ' B' : number_format($bytes / 1024, 1) . ' KB';
}
