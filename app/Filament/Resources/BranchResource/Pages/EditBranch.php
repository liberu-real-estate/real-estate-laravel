&lt;?php

/**
 * Page for editing an existing Branch entity.
 * 
 * This file contains the class definition for the page used to edit an existing Branch entity
 * within the Filament admin panel.
 */

namespace App\Filament\Resources\BranchResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BranchResource;

/**
 * Page class for editing Branch entities.
 * 
 * This class extends EditRecord to provide functionalities for editing Branch entities.
 */
class EditBranch extends EditRecord
{
    protected static $resource = BranchResource::class;
}
