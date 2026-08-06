<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Staff\Resources\Documents\Schemas;

use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Document')
                ->description('Zoom using Ctrl + Mouse Wheel or pinch on touch devices.')
                ->columnSpanFull()
                ->extraAlpineAttributes([
                    'x-data' => '{
                        zoom: 1,
                        zoomMin: 0.1,
                        zoomMax: 3,
                        zoomStep: 0.2,
                    }',
                ])
                ->schema([
                    ViewEntry::make('file_path')
                        ->view('filament.infolists.components.attachment-view-entry')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
