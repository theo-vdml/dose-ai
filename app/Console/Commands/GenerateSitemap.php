<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        // 1. Generate the sitemap
        Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create('/login')->setPriority(0.8))
            ->add(Url::create('/register')->setPriority(0.8))
            ->add(Url::create('/mentions-legales')->setPriority(0.3))
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
