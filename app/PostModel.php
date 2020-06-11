<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PostModel
 *
 * Represents the interface to posting of images
 */
class PostModel extends Model
{
    /**
     * Constants
     */
    const FETCH_TOP = 1;
    const FETCH_LATEST = 2;

    /**
     * Check if file is a valid image
     *
     * @param string $imgFile
     * @return boolean
     */
    private static function isValidImage($imgFile)
    {
        $imagetypes = array(
            IMAGETYPE_PNG,
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF
        );

        if (!file_exists($imgFile)) {
            return false;
        }

        foreach ($imagetypes as $type) {
            if (exif_imagetype($imgFile) === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get image type
     *
     * @param $ext
     * @param $file
     * @return mixed|null
     */
    private static function getImageType($ext, $file)
    {
        $imagetypes = array(
            array('png', IMAGETYPE_PNG),
            array('jpg', IMAGETYPE_JPEG),
            array('jpeg', IMAGETYPE_JPEG),
            array('gif', IMAGETYPE_GIF)
        );

        for ($i = 0; $i < count($imagetypes); $i++) {
            if (strtolower($ext) == $imagetypes[$i][0]) {
                if (exif_imagetype($file . '.' . $ext) == $imagetypes[$i][1])
                    return $imagetypes[$i][1];
            }
        }

        return null;
    }

    /**
     * Correct image rotation of uploaded image
     *
     * @param $filename
     * @param $image
     */
    private static function correctImageRotation($filename, &$image)
    {
        $exif = @exif_read_data($filename);

        if (!isset($exif['Orientation']))
            return;

        switch($exif['Orientation'])
        {
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, 270, 0);
                break;
            default:
                break;
        }
    }

    /**
     * Create thumb file of image
     *
     * @param $srcfile
     * @param $imgtype
     * @param $basefile
     * @param $fileext
     * @return bool
     */
    private static function createThumbFile($srcfile, $imgtype, $basefile, $fileext)
    {
        list($width, $height) = getimagesize($srcfile);

        $factor = 1.0;

        if ($width > $height) {
            if (($width >= 800) and ($width < 1000)) {
                $factor = 0.5;
            } else if (($width >= 1000) and ($width < 1250)) {
                $factor = 0.4;
            } else if (($width >= 1250) and ($width < 1500)) {
                $factor = 0.4;
            } else if (($width >= 1500) and ($width < 2000)) {
                $factor = 0.3;
            } else if ($width >= 2000) {
                $factor = 0.2;
            }
        } else {
            if (($height >= 800) and ($height < 1000)) {
                $factor = 0.5;
            } else if (($height >= 1000) and ($height < 1250)) {
                $factor = 0.4;
            } else if (($height >= 1250) and ($height < 1500)) {
                $factor = 0.4;
            } else if (($height >= 1500) and ($height < 2000)) {
                $factor = 0.3;
            } else if ($height >= 2000) {
                $factor = 0.2;
            }
        }

        $newwidth = $factor * $width;
        $newheight = $factor * $height;

        $dstimg = imagecreatetruecolor($newwidth, $newheight);
        if (!$dstimg)
            return false;

        $srcimage = null;
        switch ($imgtype) {
            case IMAGETYPE_PNG:
                $srcimage = imagecreatefrompng($srcfile);
                imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagepng($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_JPEG:
                $srcimage = imagecreatefromjpeg($srcfile);
                imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagejpeg($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_GIF:
                copy($srcfile, $basefile . "_thumb." . $fileext);
                break;
            default:
                return false;
                break;
        }

        return true;
    }

    /**
     * Process image upload
     *
     * @throws \Exception
     * @return int
     */
    public static function upload()
    {
        try {
            $attr = request()->validate([
                'image' => 'file|required',
                'description' => 'nullable|max:4096',
                'hashtags' => 'nullable',
                'nsfw' => 'nullable'
            ]);

            $user = User::where('id', '=', auth()->id())->first();

            $hashtagList = explode(' ', trim($attr['hashtags']));
            foreach ($hashtagList as $ht) {
                if ((strlen($ht) > 1) && ($ht[0] === '#')) {
                    $ht = substr($ht, 1);
                }

                if (!AppModel::isValidNameIdent($ht)) {
                    throw new \Exception(__('app.upload_hashtag_invalid', ['hashtag' => $ht]));
                }
            }

            $att = request()->file('image');
            if ($att != null) {
                $fname = $att->getClientOriginalName() . '_' . uniqid('', true) . '_' . md5($att->getClientOriginalName());
                $fext = $att->getClientOriginalExtension();
                $att->move(public_path() . '/gfx/posts/', $fname . '.' . $fext);
                if (!PostModel::isValidImage(public_path() . '/gfx/posts/' . $fname . '.' . $fext)) {
                    unlink(public_path() . '/gfx/posts/', $fname . '.' . $fext);
                    throw new Exception(__('app.post_invalid_image'));
                }

                $baseFile = public_path() . '/gfx/posts/' . $fname;
                $fullFile = $baseFile . '.' . $fext;

                if (!static::createThumbFile($fullFile, static::getImageType($fext, $baseFile), $baseFile, $fext)) {
                    throw new Exception('createThumbFile failed', 500);
                }

                if (!isset($attr['nsfw'])) {
                    $attr['nsfw'] = false;
                }

                $post = new PostModel();
                $post->image_full = $fname . '.' . $fext;
                $post->image_thumb = $fname . '_thumb.' . $fext;
                $post->description = $attr['description'];
                $post->hashtags = str_replace('#', '', trim($attr['hashtags']));
                if (strlen($post->hashtags > 0)) {
                    if ($post->hashtags[strlen($post->hashtags) - 1] !== ' ') {
                        $post->hashtags .= ' ';
                    }
                }
                $post->userId = auth()->id();
                $post->nsfw = (bool)$attr['nsfw'];
                $post->save();

                foreach ($hashtagList as $ht) {
                    TagsModel::addTag($ht);
                }

                $mentionNames = AppModel::getMentionList($attr['description']);
                foreach ($mentionNames as $name) {
                    $curUser = User::getByUsername($name);
                    if ($curUser) {
                        PushModel::addNotification(__('app.user_mentioned_short', ['name' => $user->username]), __('app.user_mentioned', ['name' => $user->username, 'item' => url('/p/' . $post->id)]), 'PUSH_MENTIONED', $curUser->id);
                    }
                }

                return $post->id;
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return 0;
    }

    /**
     * Get post by ID
     *
     * @param $id
     * @param bool $fetchLocked
     * @return mixed
     * @throws \Exception
     */
    public static function getPost($id, $fetchLocked = false)
    {
        try {
            $post = PostModel::where('id', '=', $id)->first();
            if (!$post) {
                throw new \Exception(__('app.post_not_found'));
            }

            if (($post->locked) && ($fetchLocked === false)) {
                throw new \Exception(__('app.post_is_locked'));
            }

            return $post;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch a pack of posts
     *
     * @param $type
     * @param $limit
     * @param $hashtag
     * @param $user
     * @param null $paginateFrom
     * @return mixed
     * @throws \Exception
     */
    public static function getPostPack($type, $limit, $hashtag, $user, $paginateFrom = null)
    {
        try {
            $posts = null;
            $type = (int)$type;

            if ($type === self::FETCH_TOP) {
                if ($paginateFrom !== null) {
                    $posts = PostModel::where('locked', '=', false)->where('hearts', '<', $paginateFrom)->orderBy('hearts', 'desc');
                } else {
                    $posts = PostModel::where('locked', '=', false)->orderBy('hearts', 'desc');
                }
            } else if ($type == self::FETCH_LATEST) {
                if ($paginateFrom !== null) {
                    $posts = PostModel::where('locked', '=', false)->where('id', '<', $paginateFrom)->orderBy('id', 'desc');
                } else {
                    $posts = PostModel::where('locked', '=', false)->orderBy('id', 'desc');
                }
            } else {
                throw new \Exception('Invalid type: ' . $type);
            }

            if ($hashtag !== null) {
                $posts->where('hashtags', 'like', '%' . $hashtag . ' %');
            }

            if ($user !== null) {
                $posts->where('userId', '=', $user);
            }

            return $posts->limit($limit)->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
