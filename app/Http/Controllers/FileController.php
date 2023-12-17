<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'images';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
        'post' => ['mp3', 'mp4', 'gif', 'png', 'jpg', 'jpeg'],
        'group' => ['png', 'jpg', 'jpeg', 'gif'],
        'group_banner' => ['png', 'jpg', 'jpeg', 'gif'],
    ];

    private static function isValidType(string $type)
    {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function isValidExtension(string $type, string $extension)
    {
        $allowedExtensions = self::$systemTypes[$type];

        return in_array(strtolower($extension), $allowedExtensions);
    }

    public static function defaultAsset(string $type)
    {
        return asset('media/' . $type . '/' . self::$default);
    }

    private static function getFileName(string $type, int $id)
    {
        $fileName = null;
        switch ($type) {
            case 'profile':
                $fileName = User::find($id)->image;
                break;
            case 'post':
                $fileName = Post::find($id)->image;
                break;
            case 'group':
                $fileName = Group::find($id)->image;
                break;
            case 'group_banner':
                $fileName = Group::find($id)->banner;
                break;
        }

        return $fileName;
    }


    private static function delete(string $type, int $id)
    {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type . '/' . $existingFileName);

            switch ($type) {
                case 'profile':
                    User::find($id)->image = null;
                    break;
                case 'post':
                    Post::find($id)->image = null;
                    break;
                case 'group':
                    Group::find($id)->image = null;
                    break;
                case 'group_banner':
                    Group::find($id)->banner = null;
                    break;
            }
        }
    }


    static function get(string $type, int $id)
    {

        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        // Validation: file exists
        $fileName = self::getFileName($type, $id);
        if ($fileName) {
            return asset('media/' . $type . '/' . $fileName);
        }

        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    static function upload(UploadedFile $file, string $type, int $id)
    {
        $extension = $file->getClientOriginalExtension();

        // Validation: upload type
        if (!self::isValidType($type)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload type');
        }
        if (!self::isValidExtension($type, $extension)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload extension');
        }

        self::delete($type, $id);

        $fileName = $file->hashName(); // generate a random unique id


        switch ($type) {
            case 'profile':
                $user = User::findOrFail($id);
                if ($user) {
                    $user->image = $fileName;
                    $user->save();
                } else {
                    redirect()->back()->with('error', 'Error: Unknown user');
                }
                break;

            case 'post':
                $post = Post::findOrFail($id);
                if ($post) {
                    $post->image = $fileName;
                    $post->save();
                } else {
                    redirect()->back()->with('error', 'Error: Unknown user');
                }
                break;
            case 'group':
                $group = Group::findOrFail($id);
                if ($group) {
                    $group->image = $fileName;
                    $group->save();
                } else {
                    redirect()->back()->with('error', 'Error: Unknown user');
                }
                break;
            case 'group_banner':
                $group = Group::findOrFail($id);
                if ($group) {
                    $group->banner = $fileName;
                    $group->save();
                } else {
                    redirect()->back()->with('error', 'Error: Unknown user');
                }
                break;

            default:
                redirect()->back()->with('error', 'Error: Unsupported upload object');
        }

        $file->storeAs($type, $fileName, self::$diskName);
        return redirect()->back()->with('success', 'Success: upload completed!');
    }

}
