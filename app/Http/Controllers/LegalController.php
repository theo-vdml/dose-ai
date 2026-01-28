<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LegalController extends Controller
{
    public function index()
    {
        $path = resource_path('content/legal.md');

        if (! file_exists($path)) {
            abort(404);
        }

        $updated_at = Carbon::createFromTimestamp(filemtime($path))->translatedFormat('d F Y');

        $rawContent = file_get_contents($path);
        if (str_contains($rawContent, '[[COOKIE_TABLE]]')) {

            if (! View::exists('legal.cookie_table')) {
                $rawContent = str_replace('[[COOKIE_TABLE]]', '', $rawContent);
            } else {
                $tableHtml = View::make('legal.cookie_table')->render();
                $tableHtml = preg_replace('/>\s+</', '><', $tableHtml);
                $rawContent = str_replace('[[COOKIE_TABLE]]', $tableHtml, $rawContent);
            }
        }

        $htmlContent = Str::markdown($rawContent);

        $title = 'Informations Légales';
        $htmlContent = preg_replace_callback('/<h1[^>]*>(.*?)<\/h1>/si', function ($matches) use (&$title) {
            $title = html_entity_decode(strip_tags($matches[1]));

            return '';
        }, $htmlContent, 1);

        $description = 'Tout ce que vous devez savoir sur l\'utilisation de PersonAI.';
        $htmlContent = preg_replace_callback('/<p[^>]*>(.*?)<\/p>/si', function ($matches) use (&$description) {
            $description = html_entity_decode(strip_tags($matches[1]));

            return '';
        }, $htmlContent, 1);

        $toc = [];
        $htmlContent = preg_replace_callback('/<h([2-3-4])>(.*?)<\/h\1>/', function ($matches) use (&$toc) {
            $level = (int) $matches[1];
            $title = strip_tags($matches[2]);
            $slug = Str::slug($title);

            $toc[] = [
                'id' => $slug,
                'level' => $level,
                'title' => $title,
            ];

            return "<h{$level} id=\"{$slug}\">{$matches[2]}</h{$level}>";
        }, $htmlContent);

        return Inertia::render('Legal', [
            'title' => $title,
            'description' => $description,
            'updated_at' => $updated_at,
            'content' => $htmlContent,
            'toc' => $toc,
        ]);
    }
}
