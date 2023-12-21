<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;

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
                $fileName = Post::find($id)->attachment;
                break;

            case 'groupProfile':
                $fileName = Group::find($id)->image;

            case 'group':
                $fileName = Group::find($id)->image;
                break;
            case 'group_banner':
                $fileName = Group::find($id)->banner;
                break;
        }

        return $fileName;
    }


    static function delete(string $type, int $id)
    {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type . '/' . 'original_' . $existingFileName);
            Storage::disk(self::$diskName)->delete($type . '/' . 'medium_' . $existingFileName);
            Storage::disk(self::$diskName)->delete($type . '/' . 'small_' . $existingFileName);

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


    static function get(string $type, int $id, string $size = 'original')
    {

        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        // Validation: file exists
        $fileName = self::getFileName($type, $id);
        if ($fileName) {
            return asset('media/' . $type . '/' . $size . '_' . $fileName);
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
                    $post->attachment = $fileName;
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

        $manager = new ImageManager(new Driver());

        $mediumFile = $manager->read($file);


        if ($type === 'group_banner') {
            $mediumFile->cover(1900, 250);
        } else {
            $mediumFile->cover(250, 250);
        }

        $smallFile = $manager->read($file);

        if ($type === 'group_banner') {
            $smallFile->cover(900, 150);
        } else {
            $smallFile->cover(100, 100);
        }

        $file->storeAs($type, 'original_' . $fileName, self::$diskName);

        $mediumFile->encode(new AutoEncoder())->save(public_path('media/' . $type . '/medium_' . $fileName));

        $smallFile->encode(new AutoEncoder)->save(public_path('media/' . $type . '/small_' . $fileName));

        return redirect()->back()->with('success', 'Success: upload completed!');
    }

}
