<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Modules\ModuleManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct(protected ModuleManager $moduleManager)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'modules' => $this->moduleManager->getAllModulesInfo(),
        ]);
    }

    public function show(string $name): JsonResponse
    {
        $info = $this->moduleManager->getModuleInfo($name);

        if (empty($info)) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        return response()->json(['module' => $info]);
    }

    public function enable(string $name): JsonResponse
    {
        try {
            $result = $this->moduleManager->enable($name);
            return response()->json(['success' => $result, 'message' => "Module {$name} enabled"]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function disable(string $name): JsonResponse
    {
        try {
            $result = $this->moduleManager->disable($name);
            return response()->json(['success' => $result, 'message' => "Module {$name} disabled"]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'total' => $this->moduleManager->all()->count(),
            'enabled' => $this->moduleManager->enabled()->count(),
            'disabled' => $this->moduleManager->disabled()->count(),
        ]);
    }
}
