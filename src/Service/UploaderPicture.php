<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class UploaderPicture{

    public function __construct(
        private Filesystem $fs,
        private $profileFolder,
        private $profileFolderPublic
        )
    {
        
    }
    
    public function uploadProfileImage($picture, $oldPicture = null){

        $folder = $this->profileFolder;

        $extension = $picture->guessExtension() ?? 'bin';
        $filename = bin2hex(random_bytes(10)) . '.' . $extension;
        $picture->move($folder, $filename);

        if ($oldPicture) {
            $this->fs->remove($folder . '/' . pathinfo($oldPicture , PATHINFO_BASENAME));
        }

        return $this->profileFolderPublic . '/' . $filename;
    }
}