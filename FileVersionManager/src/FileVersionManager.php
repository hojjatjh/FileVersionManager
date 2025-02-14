<?php
class FileVersionManager {
    private string $storagePath;
    private string $versionFile;
    private float $maxFileSize = 9.98 * 1024 * 1024 * 1024;

    public function __construct(string $storagePath = "versions") {
        $this->storagePath = rtrim($storagePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->versionFile = $this->storagePath . "versions.json";

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }

        if (!file_exists($this->versionFile)) {
            file_put_contents($this->versionFile, json_encode([]));
        }

        $htaccessPath = $this->storagePath . ".htaccess";
        if (!file_exists($htaccessPath)) {
            file_put_contents($htaccessPath, "deny from all\n<Files ~ \".*\">\ndenied\n</Files>");
        }
    }

    private function hashFile(string $filePath): string {
        if (filesize($filePath) > $this->maxFileSize) {
            throw new Exception("File exceeds the maximum allowed size of 9.98GB.");
        }

        $handle      = fopen($filePath, "rb");
        $hashContext = hash_init("sha256");

        while (!feof($handle)) {
            hash_update($hashContext, fread($handle, 1048576));
        }
        fclose($handle);

        return hash_final($hashContext);
    }

    public function saveVersion(string $filePath, string $key, string $comment = ""): void {
        if (!file_exists($filePath)) {
            throw new Exception("File does not exist: " . $filePath);
        }
        
        if (filesize($filePath) > $this->maxFileSize) {
            throw new Exception("File exceeds the maximum allowed size of 9.98GB.");
        }

        $versions = $this->getStoredVersions();
        if (isset($versions[$key])) {
            throw new Exception("Version key already exists.");
        }

        $hash       = $this->hashFile($filePath);
        $timestamp  = date("Y-m-d H:i:s");
        $fileSize   = filesize($filePath);

        $versions[$key] = [
            'hash'      => $hash,
            'timestamp' => $timestamp,
            'comment'   => $comment,
            'size'      => $fileSize
        ];

        file_put_contents($this->versionFile, json_encode($versions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function verifyVersion(string $filePath, string $key): bool {
        if (!file_exists($filePath)) {
            throw new Exception("File does not exist: " . $filePath);
        }
        
        if (filesize($filePath) > $this->maxFileSize) {
            throw new Exception("File exceeds the maximum allowed size of 9.98GB.");
        }

        $versions = $this->getStoredVersions();
        if (!isset($versions[$key])) {
            throw new Exception("Version key not found.");
        }

        $hash = $this->hashFile($filePath);
        return $versions[$key]['hash'] === $hash;
    }

    public function deleteVersion(string $key): void {
        $versions = $this->getStoredVersions();
        if (!isset($versions[$key])) {
            throw new Exception("Version key not found.");
        }
        unset($versions[$key]);
        file_put_contents($this->versionFile, json_encode($versions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function fetchVersionInfo(string $key): array {
        $versions = $this->getStoredVersions();
        if (!isset($versions[$key])) {
            throw new Exception("Version key not found.");
        }
        return [
            'timestamp'=> $versions[$key]['timestamp'],
            'comment'  => $versions[$key]['comment'],
            'size'     => $versions[$key]['size']
        ];
    }

    public function countVersions(): int {
        return count($this->getStoredVersions());
    }

    private function getStoredVersions(): array {
        return json_decode(file_get_contents($this->versionFile), true) ?? [];
    }
}
?>