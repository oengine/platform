<?php

namespace OEngine\Platform\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class PlatformActiveCommand extends Command
{
    protected $name = 'platform:active';


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
        $this->components->info('Generating optimized symbolic targets.');
        $type = $this->option('type');
        $this->components->info($type);
        return 0;
    }
}
