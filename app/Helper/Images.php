<?php
    if (! function_exists('optimizedImage')) {
        function optimizedImage($path) {
            $filename = \Illuminate\Support\Str::uuid();
            $compressImgPath = 'items/compressed/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;
            $thumbsImgPath = 'items/thumbs/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;

            // Compressed Image
            $compressedImgWebp = Image::make(Storage::path($path))->resize(900, 900)->encode('webp');
            Storage::put($compressImgPath.'.webp', $compressedImgWebp);

            $compressedImgJpg = Image::make(Storage::path($path))->resize(900, 900)->encode('jpg');
            Storage::put($compressImgPath.'.jpg', $compressedImgJpg);

            // Thumbs
            $thumbsImg = Image::make(Storage::path($path))->resize(300, 300)->encode('jpg');
            Storage::put($thumbsImgPath.'.jpg', $thumbsImg);

            return [
                'compressed_img_webp' => $compressImgPath.'.webp',
                'compressed_img_jpg' => $compressImgPath.'.jpg',
                'thumbs_img' => $thumbsImgPath.'.jpg'
            ];
        }
    }

    if (! function_exists('deleteImagesFromModel')) {
        function deleteImagesFromModel($image) {
            if (Storage::exists($image->image_path))
                Storage::delete($image->image_path);

            if (Storage::exists($image->compressed_image_path))
                Storage::delete($image->compressed_image_path);

            if (Storage::exists($image->compressed_image_jpg_path))
                Storage::delete($image->compressed_image_jpg_path);

            if (Storage::exists($image->thumbs_image_path))
                Storage::delete($image->thumbs_image_path);
        }
    }

    if (! function_exists('cloneImageFromModel')) {
        function cloneImageFromModel($image) {
            $newImagePath = 'items/original/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.\Illuminate\Support\Str::uuid().'.'.pathinfo($image->image_path, PATHINFO_EXTENSION);
            Storage::copy($image->image_path, $newImagePath);

            return $newImagePath;
        }
    }

    if (! function_exists('temporaryImageUpload')) {
        function temporaryImageUpload($inputImage)
        {
            $today = date('Y-m-d');
            $today = strtotime($today);
            $path = 'd/i/'.$today;
            $storagePath = 'public/'.$path;
            $storedImage = $inputImage->store($storagePath);
            $imagePath = '/'.str_replace("public", 'storage/public', $storedImage);
            return $imagePath;
        }
    }

    if (! function_exists('removeTemporaryImage')) {
        function removeTemporaryImage($imageUrl)
        {
            $imageUrl = str_replace('storage', 'public', $imageUrl);

            $sourceImageArr = explode('/', $imageUrl);
            $sourceImageName = $sourceImageArr[3] ?? null;
            if ($sourceImageName == null || $sourceImageName != 'draft') return false;
            if(Storage::exists($imageUrl)) {
                Storage::delete( $imageUrl);
                return true;
            }
            return false;
        }
    }

    if (! function_exists('imageMove')) {
        function imageMove($sourceImage, $folderName = 'default', $imageStoreTypes = ['image_path', 'compressed_image_path', 'thumbs_image_path'], $customWidth = 300, $customHeight = 300)
        {
            $sourceImage = str_replace("/storage", '', $sourceImage);
            $sourceImageArr = explode('/', $sourceImage);
            $sourceImageName = end($sourceImageArr);
            $today = date('Y-m-d');
            $today = strtotime($today);

            $imagePath = [];
            foreach ($imageStoreTypes as $type) {
                $typeFolder = '';
                foreach (preg_split('#[^a-z]+#i', $type, -1, PREG_SPLIT_NO_EMPTY) as $word) {
                    $typeFolder .= $word[0];
                }
                $path = 'u/i/'.substr($folderName, 0, 3).'/'.$today.'/'.$typeFolder;
                $storagePath = 'public/'.$path.'/'.$sourceImageName;

                $image = Image::make(Storage::get($sourceImage));

                $mime = $image->mime();  //edited due to updated to 2.x
                if ($mime == 'image/jpeg')
                    $extension = 'jpg';
                elseif ($mime == 'image/png')
                    $extension = 'png';
                elseif ($mime == 'image/gif')
                    $extension = 'gif';
                else
                    $extension = 'jpg';

                if(Storage::exists($storagePath)) {
                    Storage::delete( $storagePath);
                }
                switch ($type) {
                    case 'image_path':
                        Storage::copy($sourceImage, $storagePath);
                        break;
                    case 'custom_compressed':
                        if ($image->mime() == 'image/gif') {
                            Storage::copy($sourceImage, $storagePath);
                            break;
                        }
                        $image->resize($customWidth, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        Storage::put($storagePath, (string) $image->encode('webp', 100));

                        $image = Image::make(Storage::get($sourceImage));
                        $image->resize($customWidth*1.75, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $safariStoragePath = 'public/'.$path.'/sa/'.$sourceImageName;
                        Storage::put($safariStoragePath, (string) $image->encode($extension));
                        break;
                    case 'resize_compressed':
                        if ($image->mime() == 'image/gif') {
                            Storage::copy($sourceImage, $storagePath);
                            break;
                        }
                        $image->resize($customWidth, $customHeight);
                        Storage::put($storagePath, (string) $image->encode('webp', 100));

                        $image = Image::make(Storage::get($sourceImage));
                        $image->resize($customWidth*1.75, $customHeight*1.75);
                        $safariStoragePath = 'public/'.$path.'/sa/'.$sourceImageName;
                        Storage::put($safariStoragePath, (string) $image->encode($extension));
                        break;
                    case 'compressed_image_path':
                        if ($image->mime() == 'image/gif') {
                            Storage::copy($sourceImage, $storagePath);
                            break;
                        }
                        $image->resize(640, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        Storage::put($storagePath, (string) $image->encode('webp', 100));

                        $image = Image::make(Storage::get($sourceImage));
                        $image->resize(960, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $safariStoragePath = 'public/'.$path.'/sa/'.$sourceImageName;
                        Storage::put($safariStoragePath, (string) $image->encode($extension));
                        break;
                    case 'thumbs_image_path':
                        if ($image->mime() == 'image/gif') {
                            Storage::copy($sourceImage, $storagePath);
                            break;
                        }
                        $image->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        Storage::put($storagePath, (string) $image->encode('webp', 100));

                        $image = Image::make(Storage::get($sourceImage));
                        $image->resize(300, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $safariStoragePath = 'public/'.$path.'/sa/'.$sourceImageName;
                        Storage::put($safariStoragePath, (string) $image->encode($extension));
                        break;
                    case 'resize_thumbs_image_path':
                        if ($image->mime() == 'image/gif') {
                            Storage::copy($sourceImage, $storagePath);
                            break;
                        }
                        $image->resize(300, 300);
                        Storage::put($storagePath, (string) $image->encode('webp', 100));

                        $image = Image::make(Storage::get($sourceImage));
                        $image->resize(300, 300);
                        $safariStoragePath = 'public/'.$path.'/sa/'.$sourceImageName;
                        Storage::put($safariStoragePath, (string) $image->encode($extension));
                        break;
                    default:
                        Storage::copy($sourceImage, $storagePath);
                        break;
                }
                $imagePath [$type] = '/'.str_replace("public", 'storage/public', $storagePath);
            }

            Storage::delete( $sourceImage);
            return $imagePath;
        }
    }

    if (! function_exists('imageDelete')) {
        function imageDelete($imageUrl, $permanentDelete = false)
        {
            $imageUrl = str_replace('storage', 'public', $imageUrl);
            $trashPath = str_replace('u', 't', $imageUrl);
            if(Storage::exists($imageUrl)) {
                if ($permanentDelete) {
                    Storage::delete( $imageUrl);
                } else {
                    Storage::move($imageUrl, $trashPath);
                }
                return true;
            }
            return false;
        }
    }

    if (! function_exists('temporaryImageClone')) {
        function temporaryImageClone($sourceImage)
        {
            $sourceImageArr = explode('/', $sourceImage);
            if($sourceImageArr[1] != 'storage') {
                $sourceImage = '/storage/'.$sourceImage;
            }
            $sourceImage = str_replace("storage", 'public', $sourceImage);
            $sourceImageName = end($sourceImageArr);
            $extArr = explode('.', $sourceImageName);
            $storeImageName = Uuid::generate()->string.'.'.end($extArr);

            $today = date('Y-m-d');
            $today = strtotime($today);
            $path = 'd/i/'.$today;
            $storagePath = 'public/'.$path.'/'.$storeImageName;
            if(Storage::exists($storagePath)) {
                Storage::delete( $storagePath);
            }
            Storage::copy($sourceImage, $storagePath);
            $imagePath = '/'.str_replace("public", 'storage', $storagePath);
            return $imagePath;
        }
    }
    if (! function_exists('temporaryImageUploadFromUrl')) {
        function temporaryImageUploadFromUrl($url)
        {
            $info = pathinfo($url);
            $extArr = explode('.', $info['basename']);
            $storeImageName = Uuid::generate()->string.'.'.end($extArr);
            $inputImage = file_get_contents($url);
            $today = date('Y-m-d');
            $today = strtotime($today);
            $path = 'd/i/'.$today;
            $storagePath = 'public/'.$path;

            Storage::put($storagePath.'/'.$storeImageName, $inputImage);
            $storedImage = $storagePath.'/'.$storeImageName;
            $imagePath = '/'.str_replace("public", 'storage', $storedImage);
            return $imagePath;
        }
    }
