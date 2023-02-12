<?php

namespace OEngine\Platform\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class PlatformListCommand extends Command
{
    protected $name = 'platform:list';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', null, InputOption::VALUE_OPTIONAL, 'Recreate existing symbolic targets.', 'module'],
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $platform = platform_by($type);
        $this->components->info('Platform:' . $type);
        foreach ($platform->getData() as $item) {
            $this->components->info($item->name . ':' . ($item->isActive() ? 'Actived' : 'UnActived'));
        }
        return 0;
    }
}
