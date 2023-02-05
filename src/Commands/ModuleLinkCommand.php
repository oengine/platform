<?php

namespace OEngine\Platform\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use OEngine\Platform\Facades\Module;
use OEngine\Platform\Facades\Theme;
use Symfony\Component\Console\Input\InputOption;

class ModuleLinkCommand extends Command
{
    protected $name = 'module:link';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['reload', null, InputOption::VALUE_OPTIONAL, 'reload app.', null],
            ['relative', null, InputOption::VALUE_OPTIONAL, 'Create the symbolic target using relative path.', null],
            ['force', null, InputOption::VALUE_OPTIONAL, 'Recreate existing symbolic targets.', null],
        ];
    }
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the symbolic targets configured for the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Generating optimized symbolic targets.');
        $arr = [config('platform.appdir.theme', 'themes'), config('platform.appdir.module', 'modules'), config('platform.appdir.plugin', 'plugins')];
        $root_path = config('platform.appdir.root', 'platform');
        foreach ($arr as $item) {
            $public = (public_path($item));
            $appdir = (base_path($root_path . '/' . $item));
            if (true) {
                $this->laravel->make('files')->deleteDirectory($public);
            }
            $this->laravel->make('files')->ensureDirectoryExists($public);
            $this->laravel->make('files')->ensureDirectoryExists($appdir);
        }
        $force = $this->option('force') || true;
        $relative = $this->option('relative') || false;
        Theme::findAndActive('oengine-none', true);
        Theme::findAndActive(get_option('page_site_theme'), true);
        Theme::findAndActive(get_option('page_admin_theme'), true);
        Log::info(Module::getLinks());
        foreach (Module::getLinks() as  [
            'source' => $source,
            'target' => $target
        ]) {
            try {
                $target = ($target);
                $source = (($source));
                $this->components->info("The [$target] target has been connected to [$source].");
                if (file_exists($target) && !$this->isRemovableSymtarget($target, $force)) {
                    $this->components->error("The [$target] target already exists.");
                    continue;
                }

                if (is_link($target)) {
                    $this->laravel->make('files')->delete($target);
                }

                if (($relative)) {
                    $this->laravel->make('files')->relativeLink($source, $target);
                    $this->components->info("The [$target] relativeLink has been connected to [$source].");
                } else {
                    $this->laravel->make('files')->link($source, $target);
                    $this->components->info("The [$target] target has been connected to [$source].");
                }
            } catch (\Exception $e) {
            }
        }
        $this->call('storage:target');

        return 0;
    }
    /**
     * Determine if the provided path is a symtarget that can be removed.
     *
     * @param  string  $target
     * @param  bool  $force
     * @return bool
     */
    protected function isRemovableSymtarget(string $target, bool $force): bool
    {
        return is_link($target) && $force;
    }
}
