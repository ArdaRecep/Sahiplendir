<?php

namespace App\Observers;

use App\Models\Section;
use Illuminate\Support\Facades\File;

class SectionObserver
{
    public function created(Section $section): void
    {
        self::createViews($section->slug);
    }

    public function updated(Section $section): void
    {
        // Eğer slug değiştiyse, eski dosyaları yeniden adlandır
        if ($section->isDirty('slug')) {
            $oldSlug = $section->getOriginal('slug');
            $newSlug = $section->slug;

            self::renameViews($oldSlug, $newSlug);
        }
    }

    public function deleted(Section $section): void
    {
        self::deleteViews($section->slug);
    }

    protected static function createViews(string $slug): void
{
    $adminViewPath = resource_path("views/sections/admin/{$slug}.php");
    $frontViewPath = resource_path("views/sections/front/{$slug}.blade.php");

    if (!File::exists(dirname($adminViewPath))) {
        File::makeDirectory(dirname($adminViewPath), 0755, true);
    }

    if (!File::exists(dirname($frontViewPath))) {
        File::makeDirectory(dirname($frontViewPath), 0755, true);
    }

    if (!File::exists($adminViewPath)) {
        $adminContent = <<<PHP
<?php

use App\Models\Page;
use App\Models\Section as SectionModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use App\Helpers\FormHelpers;

\$update_form_inputs = [
    FormHelpers::getSectionSettings(),
    Section::make(__("admin.section_content_management"))
        ->description(__("admin.manage_section_content", ['section' => '{$slug}']))
        ->schema([
            //
        ])
        ->collapsible(),
];
PHP;
        File::put($adminViewPath, $adminContent);
    }

    if (!File::exists($frontViewPath)) {
        $frontContent = <<<BLADE
<section>
    <!-- {$slug} front görünümü -->
</section>
BLADE;
        File::put($frontViewPath, $frontContent);
    }
}


    protected static function renameViews(string $oldSlug, string $newSlug): void
    {
        $oldAdminPath = resource_path("views/sections/admin/{$oldSlug}.blade.php");
        $newAdminPath = resource_path("views/sections/admin/{$newSlug}.blade.php");

        $oldFrontPath = resource_path("views/sections/front/{$oldSlug}.blade.php");
        $newFrontPath = resource_path("views/sections/front/{$newSlug}.blade.php");

        if (File::exists($oldAdminPath)) {
            File::move($oldAdminPath, $newAdminPath);
        }

        if (File::exists($oldFrontPath)) {
            File::move($oldFrontPath, $newFrontPath);
        }
    }

    protected static function deleteViews(string $slug): void
    {
        $adminViewPath = resource_path("views/sections/admin/{$slug}.blade.php");
        $frontViewPath = resource_path("views/sections/front/{$slug}.blade.php");

        if (File::exists($adminViewPath)) {
            File::delete($adminViewPath);
        }

        if (File::exists($frontViewPath)) {
            File::delete($frontViewPath);
        }
    }
}
