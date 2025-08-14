<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

if (!function_exists('setSubSidebarActive')) {
    function setSubSidebarActive($route)
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r)) {
                    return 'open';
                }
            }
        } else {
            if (request()->routeIs($route)) {
                return 'open';
            }
        }

        return '';
    }
}

if (!function_exists('setSidebarActive')) {
    function setSidebarActive($route)
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r)) {
                    return 'active';
                }
            }
        } else {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }

        return '';
    }
}

if (!function_exists('handleUpload')) {
    function handleUpload($inputName, $directory = 'uploads', $oldImagePathValue = null)
    {
        try {
            if (request()->hasFile($inputName)) {
                if ($oldImagePathValue && File::exists(public_path($oldImagePathValue)) && !in_array($oldImagePathValue, ['/avatars/1.png'])) {
                    File::delete(public_path($oldImagePathValue));
                }

                $file = request()->file($inputName);

                $fileName = uniqid() . '_' . trim($file->getClientOriginalName());

                $destinationPath = public_path($directory);

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $fileName);

                return "{$directory}/{$fileName}";
            }
        } catch (\Exception $e) {
            Log::error("File upload error: " . $e->getMessage());
            return null;
        }

        return null;
    }
}

if (!function_exists('deleteFileIfExist')) {
    function deleteFileIfExist($filePath)
    {
        try {
            if (File::exists(public_path($filePath))) {
                File::delete(public_path($filePath));
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}

if (!function_exists('passwordPlainText')) {
    function passwordPlainText()
    {
        return '&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;';
    }
}
