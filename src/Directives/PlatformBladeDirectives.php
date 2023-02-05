<?php

namespace OEngine\Platform\Directives;

use Illuminate\Support\Str;
use Livewire\LivewireManager;

class PlatformBladeDirectives
{
    public static function EndIf()
    {
        return <<<EOT
        <?php
            endif;
        ?>
        EOT;
    }
    public static function CheckRole($role)
    {
        return <<<EOT
        <?php
        \$auth=auth();
        \$user=\$auth->user();
        if(\$auth->check() &&( \$user->isSuperAdmin() || \$user->hasRole('{$role}'))) :
        ?>
        EOT;
    }
    public static function PlatformHead($expression)
    {
        $expression = Str::upper($expression);
        return <<<EOT
        <?php
            do_action('PLATFORM_HEAD_{$expression}');
        ?>
        EOT;
    }

    public static function PlatformBody($expression)
    {
        $expression = Str::upper($expression);
        return <<<EOT
        <?php
        do_action('PLATFORM_BODY_{$expression}');
        ?>
        EOT;
    }
}
