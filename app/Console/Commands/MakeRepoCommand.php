<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeRepoCommand extends Command
{
    protected $signature = 'make:repo {name : The model name (e.g. Product)}';
    protected $description = 'Generate Repository pattern files: Model, Interface, Repository, Service, Controller, Resource, Requests, and Routes';

    public function __construct(private Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));

        $type = $this->choice('What type do you want to generate?', [
            'api',
            'dashboard',
            'both',
        ], 0);

        // Always generate these
        $this->createModel($name);
        $this->createInterface($name);
        $this->createRepository($name);
        $this->createService($name);

        // Based on choice
        if (in_array($type, ['api', 'both'])) {
            $this->createApiController($name);
            $this->createResource($name);
            $this->createRequests($name);
            $this->appendApiRoute($name);
        }

        if (in_array($type, ['dashboard', 'both'])) {
            $this->createDashboardController($name);
            $this->appendDashboardRoute($name);
        }

        $this->newLine();
        $this->info("🎉 All files for [{$name}] have been created successfully!");
        $this->newLine();
        $this->warn("Don't forget to register the binding in AppServiceProvider:");
        $this->line("  \$this->app->bind(\\App\\Repositories\\Interfaces\\{$name}RepositoryInterface::class, \\App\\Repositories\\{$name}Repository::class);");
        $this->newLine();

        return self::SUCCESS;
    }

    // ═══════════════════════════════════════════
    //  Model
    // ═══════════════════════════════════════════

    private function createModel(string $name): void
    {
        $path = app_path("Models/{$name}.php");

        if ($this->files->exists($path)) {
            $this->warn("Model already exists: Models/{$name}.php — skipped");
            return;
        }

        $stub = <<<PHP
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class {$name} extends Model
        {
            use HasFactory;

            protected \$fillable = [
                //
            ];
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Models/{$name}.php");
    }

    // ═══════════════════════════════════════════
    //  Interface
    // ═══════════════════════════════════════════

    private function createInterface(string $name): void
    {
        $path = app_path("Repositories/Interfaces/{$name}RepositoryInterface.php");

        $stub = <<<PHP
        <?php

        namespace App\Repositories\Interfaces;

        interface {$name}RepositoryInterface
        {
            public function all();

            public function find(int \$id);

            public function create(array \$data);

            public function update(int \$id, array \$data);

            public function delete(int \$id);
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Repositories/Interfaces/{$name}RepositoryInterface.php");
    }

    // ═══════════════════════════════════════════
    //  Repository
    // ═══════════════════════════════════════════

    private function createRepository(string $name): void
    {
        $path = app_path("Repositories/{$name}Repository.php");

        $stub = <<<PHP
        <?php

        namespace App\Repositories;

        use App\Models\\{$name};
        use App\Repositories\Interfaces\\{$name}RepositoryInterface;

        class {$name}Repository implements {$name}RepositoryInterface
        {
            public function __construct(private {$name} \$model)
            {
            }

            public function all()
            {
                return \$this->model->all();
            }

            public function find(int \$id)
            {
                return \$this->model->findOrFail(\$id);
            }

            public function create(array \$data)
            {
                return \$this->model->create(\$data);
            }

            public function update(int \$id, array \$data)
            {
                \$record = \$this->find(\$id);
                \$record->update(\$data);

                return \$record;
            }

            public function delete(int \$id)
            {
                return \$this->find(\$id)->delete();
            }
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Repositories/{$name}Repository.php");
    }

    // ═══════════════════════════════════════════
    //  Service
    // ═══════════════════════════════════════════

    private function createService(string $name): void
    {
        $path = app_path("Services/{$name}Service.php");

        $stub = <<<PHP
        <?php

        namespace App\Services;

        use App\Repositories\Interfaces\\{$name}RepositoryInterface;

        class {$name}Service
        {
            public function __construct(private {$name}RepositoryInterface \$repo)
            {
            }

            public function getAll()
            {
                return \$this->repo->all();
            }

            public function findById(int \$id)
            {
                return \$this->repo->find(\$id);
            }

            public function create(array \$data)
            {
                return \$this->repo->create(\$data);
            }

            public function update(int \$id, array \$data)
            {
                return \$this->repo->update(\$id, \$data);
            }

            public function delete(int \$id)
            {
                return \$this->repo->delete(\$id);
            }
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Services/{$name}Service.php");
    }

    // ═══════════════════════════════════════════
    //  API Controller
    // ═══════════════════════════════════════════

    private function createApiController(string $name): void
    {
        $path = app_path("Http/Controllers/Api/{$name}Controller.php");

        $stub = <<<PHP
        <?php

        namespace App\Http\Controllers\Api;

        use App\Http\Controllers\Controller;
        use App\Http\Requests\\{$name}\\Store{$name}Request;
        use App\Http\Requests\\{$name}\\Update{$name}Request;
        use App\Http\Resources\\{$name}Resource;
        use App\Services\\{$name}Service;

        class {$name}Controller extends Controller
        {
            public function __construct(private {$name}Service \$service)
            {
            }

            public function index()
            {
                return {$name}Resource::collection(\$this->service->getAll());
            }

            public function store(Store{$name}Request \$request)
            {
                \$record = \$this->service->create(\$request->validated());

                return new {$name}Resource(\$record);
            }

            public function show(int \$id)
            {
                return new {$name}Resource(\$this->service->findById(\$id));
            }

            public function update(Update{$name}Request \$request, int \$id)
            {
                \$record = \$this->service->update(\$id, \$request->validated());

                return new {$name}Resource(\$record);
            }

            public function destroy(int \$id)
            {
                \$this->service->delete(\$id);

                return response()->json(['message' => '{$name} deleted successfully']);
            }
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Http/Controllers/Api/{$name}Controller.php");
    }

    // ═══════════════════════════════════════════
    //  Dashboard Controller
    // ═══════════════════════════════════════════

    private function createDashboardController(string $name): void
    {
        $path = app_path("Http/Controllers/Dashboard/{$name}Controller.php");
        $viewFolder = Str::snake($name, '-');
        $varPlural = Str::camel(Str::plural($name));
        $varSingular = Str::camel($name);

        $stub = <<<PHP
        <?php

        namespace App\Http\Controllers\Dashboard;

        use App\Http\Controllers\Controller;
        use App\Services\\{$name}Service;

        class {$name}Controller extends Controller
        {
            public function __construct(private {$name}Service \$service)
            {
            }

            public function index()
            {
                \${$varPlural} = \$this->service->getAll();

                return view('dashboard.{$viewFolder}.index', compact('{$varPlural}'));
            }

            public function create()
            {
                return view('dashboard.{$viewFolder}.create');
            }

            public function store()
            {
                // TODO: Add form request validation
                \$this->service->create(request()->all());

                return redirect()->route('dashboard.{$viewFolder}.index')
                    ->with('success', '{$name} created successfully');
            }

            public function edit(int \$id)
            {
                \${$varSingular} = \$this->service->findById(\$id);

                return view('dashboard.{$viewFolder}.edit', compact('{$varSingular}'));
            }

            public function update(int \$id)
            {
                // TODO: Add form request validation
                \$this->service->update(\$id, request()->all());

                return redirect()->route('dashboard.{$viewFolder}.index')
                    ->with('success', '{$name} updated successfully');
            }

            public function destroy(int \$id)
            {
                \$this->service->delete(\$id);

                return redirect()->route('dashboard.{$viewFolder}.index')
                    ->with('success', '{$name} deleted successfully');
            }
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Http/Controllers/Dashboard/{$name}Controller.php");
    }

    // ═══════════════════════════════════════════
    //  API Resource
    // ═══════════════════════════════════════════

    private function createResource(string $name): void
    {
        $path = app_path("Http/Resources/{$name}Resource.php");

        $stub = <<<PHP
        <?php

        namespace App\Http\Resources;

        use Illuminate\Http\Request;
        use Illuminate\Http\Resources\Json\JsonResource;

        class {$name}Resource extends JsonResource
        {
            public function toArray(Request \$request): array
            {
                return parent::toArray(\$request);
            }
        }
        PHP;

        $this->put($path, $stub);
        $this->info("✔ Created: app/Http/Resources/{$name}Resource.php");
    }

    // ═══════════════════════════════════════════
    //  Form Requests
    // ═══════════════════════════════════════════

    private function createRequests(string $name): void
    {
        foreach (['Store', 'Update'] as $prefix) {
            $path = app_path("Http/Requests/{$name}/{$prefix}{$name}Request.php");

            $stub = <<<PHP
            <?php

            namespace App\Http\Requests\\{$name};

            use Illuminate\Foundation\Http\FormRequest;

            class {$prefix}{$name}Request extends FormRequest
            {
                public function authorize(): bool
                {
                    return true;
                }

                public function rules(): array
                {
                    return [
                        //
                    ];
                }
            }
            PHP;

            $this->put($path, $stub);
            $this->info("✔ Created: app/Http/Requests/{$name}/{$prefix}{$name}Request.php");
        }
    }

    // ═══════════════════════════════════════════
    //  Routes
    // ═══════════════════════════════════════════

    private function appendApiRoute(string $name): void
    {
        $routesPath = base_path('routes/api.php');

        if (! $this->files->exists($routesPath)) {
            $this->warn("routes/api.php not found. Run: php artisan install:api");
            return;
        }

        $slug = Str::snake(Str::plural($name), '-');
        $routeLine = "\nRoute::apiResource('{$slug}', \\App\\Http\\Controllers\\Api\\{$name}Controller::class);";

        // Avoid duplicate routes
        if (Str::contains($this->files->get($routesPath), "'{$slug}'")) {
            $this->warn("API route for [{$slug}] already exists — skipped");
            return;
        }

        $this->files->append($routesPath, $routeLine);
        $this->info("✔ Route added: routes/api.php → apiResource('{$slug}')");
    }

    private function appendDashboardRoute(string $name): void
    {
        $routesPath = base_path('routes/web.php');
        $slug = Str::snake(Str::plural($name), '-');
        $routeLine = "\nRoute::resource('dashboard/{$slug}', \\App\\Http\\Controllers\\Dashboard\\{$name}Controller::class)->names('dashboard.{$slug}');";

        if (Str::contains($this->files->get($routesPath), "'dashboard/{$slug}'")) {
            $this->warn("Dashboard route for [{$slug}] already exists — skipped");
            return;
        }

        $this->files->append($routesPath, $routeLine);
        $this->info("✔ Route added: routes/web.php → resource('dashboard/{$slug}')");
    }

    // ═══════════════════════════════════════════
    //  Helpers
    // ═══════════════════════════════════════════

    private function put(string $path, string $content): void
    {
        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);
    }
}
