<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Catalogo;

class GenerateSitemap extends Command
{
    /**
     * El nombre y firma del comando en la consola.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Generar el archivo sitemap.xml con URLs estáticas y dinámicas';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // 1. URLs Estáticas Principales
        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create('/login')
                ->setPriority(0.3)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create('/register')
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        // 2. URLs dinámicas desde la base de datos
        Catalogo::query()
            ->whereNotNull('name_handle')
            ->where('name_handle', '!=', '')
            ->chunk(100, function ($catalogs) use ($sitemap) {
                foreach ($catalogs as $catalog) {
                    $sitemap->add(
                        Url::create("/{$catalog->name_handle}")
                            ->setLastModificationDate($catalog->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.8)
                    );
                }
            });

        // 3. Guardar el archivo en la carpeta pública
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('¡Sitemap generado con éxito en public/sitemap.xml!');
    }
}