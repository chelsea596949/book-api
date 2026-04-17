<?php
// 設定要掃描的目錄
$path = realpath(__DIR__ . '/app'); 

$it = new RecursiveDirectoryIterator($path);
foreach (new RecursiveIteratorIterator($it) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filename = $file->getPathname();
        $content  = file_get_contents($filename);
        
        // 檢查前三個位元組是否為 BOM (EF BB BF)
        if (substr($content, 0, 3) === pack("CCC", 0xef, 0xbb, 0xbf)) {
            echo "【發現 BOM】: $filename \n";
            
            // 如果你想自動移除，取消下面兩行的註解：
            // $cleanContent = substr($content, 3);
            // file_put_contents($filename, $cleanContent);
            // echo "  >> 已自動移除並修復。\n";
        }
    }
}
echo "檢查結束。\n";