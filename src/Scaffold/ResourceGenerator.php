<?php

namespace PretendTrue\LaravelBuilder\Scaffold;

use Illuminate\Filesystem\Filesystem;

class ResourceGenerator extends Generator
{
    /**
     * ResourceGenerator constructor.
     *
     * @param Filesystem|null $files
     */
    public function __construct(Filesystem $files = null)
    {
        $this->files = $files ?: app('files');
    }

    /**
     * Create a resource file.
     *
     * @param null $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function builder($name = null)
    {
        $className = ucfirst($name) . 'Resource';
        $path = app_path("Http/Resources/{$className}.php");
        $dir = $this->files->dirname($path);

        if ($this->files->missing($dir)) {
            $this->files->makeDirectory($dir, 0755, true);
            $this->builder();
        }

        $stubName = is_null($name) ? 'base_resource' : 'resource';
        $stub = $this->files->get($this->getStub($stubName));

        $stub = $this->replaceClassName($stub, $className)
            ->getContent($stub);

        $this->files->put($path, $stub);

        return $path;
    }
}