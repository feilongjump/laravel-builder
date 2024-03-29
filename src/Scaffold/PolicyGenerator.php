<?php

/*
 * This file is part of the pretendtrue/laravel-builder.
 *
 * (c) pretendtrue <play@pretendtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PretendTrue\LaravelBuilder\Scaffold;

use Illuminate\Filesystem\Filesystem;

class PolicyGenerator extends Generator
{
    /**
     * PolicyGenerator constructor.
     */
    public function __construct(Filesystem $files = null)
    {
        $this->files = $files ?: app('files');
    }

    /**
     * Create a policy file.
     *
     * @param null $name
     *
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function builder($name = null)
    {
        $className = "{$name}Policy";
        $path = app_path("Policies/{$className}.php");
        $dir = $this->files->dirname($path);

        if ($this->files->missing($dir)) {
            $this->files->makeDirectory($dir, 0755, true);
            $this->builder();
        }

        $stubName = is_null($name) ? 'base_policy' : 'policy';
        $stub = $this->files->get($this->getStub($stubName));

        $stub = $this->replaceClassName($stub, $className)
            ->getContent($stub);

        $this->files->put($path, $stub);

        return $path;
    }
}
