<?php
/**
 * CacheManager - Manages caching operations for improved application performance
 */
class CacheManager {
    private $cachePath;
    private $defaultExpiry = 3600; // Default cache lifetime in seconds (1 hour)
    
    /**
     * Constructor
     * 
     * @param string $cachePath Path to store cache files (default: app/cache)
     */
    public function __construct($cachePath = null) {
        $this->cachePath = $cachePath ?? __DIR__ . '/../cache';
        
        // Create cache directory if it doesn't exist
        if (!file_exists($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Generate a cache key for the given identifier
     * 
     * @param string $identifier The cache identifier
     * @return string The cache file path
     */
    private function getCacheFilePath($identifier) {
        return $this->cachePath . '/' . md5($identifier) . '.cache';
    }
    
    /**
     * Check if a cache file exists and is valid
     * 
     * @param string $identifier The cache identifier
     * @return bool True if cache exists and is valid, false otherwise
     */
    public function has($identifier) {
        $cacheFile = $this->getCacheFilePath($identifier);
        
        if (!file_exists($cacheFile)) {
            return false;
        }
        
        $cacheContent = file_get_contents($cacheFile);
        if ($cacheContent === false) {
            return false;
        }
        
        $cacheData = unserialize($cacheContent);
        if (!$cacheData || !isset($cacheData['expiry']) || !isset($cacheData['data'])) {
            return false;
        }
        
        // Check if cache has expired
        if ($cacheData['expiry'] < time()) {
            // Cache expired, remove it
            $this->remove($identifier);
            return false;
        }
        
        return true;
    }
    
    /**
     * Get data from cache
     * 
     * @param string $identifier The cache identifier
     * @return mixed The cached data or null if not found
     */
    public function get($identifier) {
        if (!$this->has($identifier)) {
            return null;
        }
        
        $cacheFile = $this->getCacheFilePath($identifier);
        $cacheContent = file_get_contents($cacheFile);
        $cacheData = unserialize($cacheContent);
        
        return $cacheData['data'];
    }
    
    /**
     * Store data in cache
     * 
     * @param string $identifier The cache identifier
     * @param mixed $data The data to store
     * @param int|null $lifetime Cache lifetime in seconds (null = use default)
     * @return bool True on success, false on failure
     */
    public function set($identifier, $data, $lifetime = null) {
        $cacheFile = $this->getCacheFilePath($identifier);
        $lifetime = $lifetime ?? $this->defaultExpiry;
        
        $cacheData = [
            'expiry' => time() + $lifetime,
            'data' => $data
        ];
        
        return file_put_contents($cacheFile, serialize($cacheData)) !== false;
    }
    
    /**
     * Remove a specific cache item
     * 
     * @param string $identifier The cache identifier
     * @return bool True if removed or didn't exist, false on error
     */
    public function remove($identifier) {
        $cacheFile = $this->getCacheFilePath($identifier);
        
        if (file_exists($cacheFile)) {
            return unlink($cacheFile);
        }
        
        return true;
    }
    
    /**
     * Clear all cache or cache with a specific prefix
     * 
     * @param string|null $prefix Only clear cache keys with this prefix
     * @return bool True on success, false on error
     */
    public function clear($prefix = null) {
        $files = glob($this->cachePath . '/*.cache');
        $success = true;
        
        foreach ($files as $file) {
            if ($prefix !== null) {
                // Get the original identifier from filename
                $cacheFile = basename($file, '.cache');
                
                // Read the file to check if we can recover the original key
                $content = file_get_contents($file);
                if ($content !== false) {
                    // For debugging only
                    // file_put_contents($this->cachePath . '/debug.log', "Checking file: $file for prefix: $prefix\n", FILE_APPEND);
                    
                    // Delete files whose original key contains the prefix
                    if (strpos($file, md5($prefix)) === 0 || $this->fileContainsPrefix($file, $prefix)) {
                        if (!unlink($file)) {
                            $success = false;
                        }
                    }
                }
            } else {
                if (!unlink($file)) {
                    $success = false;
                }
            }
        }
        
        return $success;
    }
    
    /**
     * Helper method to check if a cache file contains a key with the given prefix
     * 
     * @param string $file Path to the cache file
     * @param string $prefix Prefix to search for
     * @return bool True if the file contains a key with the prefix
     */
    private function fileContainsPrefix($file, $prefix) {
        // This is a more thorough approach, but might be resource-intensive
        // for large cache files. Simple implementation for demonstration.
        $content = file_get_contents($file);
        
        if ($content === false) {
            return false;
        }
        
        // We can't reliably check the original key without storing it in the cache data
        // This is a limitation of the current implementation
        
        return false;
    }
    
    /**
     * Get cache data if exists, otherwise execute callback and cache result
     * 
     * @param string $identifier Cache identifier
     * @param callable $callback Function to execute if cache doesn't exist
     * @param int|null $lifetime Cache lifetime in seconds
     * @return mixed The cached or newly generated data
     */
    public function remember($identifier, $callback, $lifetime = null) {
        if ($this->has($identifier)) {
            return $this->get($identifier);
        }
        
        $data = $callback();
        $this->set($identifier, $data, $lifetime);
        
        return $data;
    }
    
    /**
     * Invalidate all caches related to a specific entity
     * 
     * @param string $entity Entity name or prefix (e.g., 'group_12')
     * @return void
     */
    public function invalidateEntity($entity) {
        // Remove specific key patterns that match our naming convention
        if (strpos($entity, 'group_') === 0) {
            $groupId = substr($entity, 6); // Extract the ID part
            $this->remove("group_{$groupId}_inventories");
        } else {
            // For backward compatibility, still try the original method
            $this->clear($entity);
            
            // Also search for any keys containing the entity as a substring
            $files = glob($this->cachePath . '/*.cache');
            foreach ($files as $file) {
                $content = file_get_contents($file);
                if ($content !== false) {
                    $cacheData = @unserialize($content);
                    if ($cacheData) {
                        $cacheFile = basename($file, '.cache');
                        // Try to match cache keys that might contain our entity ID
                        if (strpos($cacheFile, md5($entity)) !== false || 
                            strpos($cacheFile, md5("{$entity}_")) !== false ||
                            strpos($cacheFile, md5("group_{$entity}")) !== false ||
                            strpos($cacheFile, md5("group_{$entity}_inventories")) !== false) {
                            unlink($file);
                        }
                    }
                }
            }
        }
    }
}