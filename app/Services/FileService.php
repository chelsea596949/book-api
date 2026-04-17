<?php
namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

class FileService
{
    /**
     * 處理單一圖片上傳
     * @param UploadedFile|null $file 上傳的檔案物件
     * @param string $path 相對於 FCPATH 的儲存路徑
     * @return string|null 傳回儲存後的檔名，失敗或無檔案則傳回 null
     */
    public function uploadImage(?UploadedFile $file, string $path='images/books'): ?string
    {
        if($file === null || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        // 封裝你之前的邏輯：生成強化的隨機名稱
        $newName = bin2hex(random_bytes(16)).'.'.$file->getExtension();

        // 確保目錄存在，move 會自動處理
        $file->move(FCPATH.$path, $newName);

        return $newName;
    }
}