<?php

namespace alecgarcia\LunarAuthorizeNet\Commands;

use Illuminate\Console\Command;

class LunarAuthorizeNetCommand extends Command
{
    public $signature = 'lunar-authorizenet';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
