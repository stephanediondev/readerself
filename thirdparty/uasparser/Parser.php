<?php
/**
 * User Agent String Parser
 *
 * PHP version 5
 *
 * @package    UASparser
 * @author     Jaroslav Mallat (http://mallat.cz/)
 * @copyright  Copyright (c) 2008 Jaroslav Mallat
 * @copyright  Copyright (c) 2010 Alex Stanev (http://stanev.org)
 * @copyright  Copyright (c) 2012 Martin van Wingerden (http://www.copernica.com)
 * @author     Marcus Bointon (https://github.com/Synchro)
 * @version    0.53
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @link       http://user-agent-string.info/download/UASparser
 */

namespace UAS;

/**
 * User Agent String Parser Class.
 * @package UASparser
 */
class Parser
{
    /**
     * How often to update the UAS database.
     * @type integer
     */
    public $updateInterval = 86400; // 1 day

    /**
     * Whether debug output is enabled.
     * @type boolean
     */
    protected $debug = false;

    /**
     * Default timeout for network requests.
     * @type integer
     */
    public $timeout = 60;

    /**
     * Should this instance attempt data downloads?
     * Useful if some other instance (e.g. from cron) is responsible for downloads.
     * @type bool
     */
    protected $doDownloads = true;

    /**
     * URL to fetch the full data file from.
     * @type string
     */
    protected static $ini_url = 'http://user-agent-string.info/rpc/get_data.php?key=free&format=ini';

    /**
     * URL to fetch the data file version from.
     * @type string
     */
    protected static $ver_url = 'http://user-agent-string.info/rpc/get_data.php?key=free&format=ini&ver=y';

    /**
     * URL to fetch the data file hash from.
     * @type string
     */
    protected static $md5_url = 'http://user-agent-string.info/rpc/get_data.php?format=ini&md5=y';

    /**
     * URL for info about the UAS project.
     * @type string
     */
    protected static $info_url = 'http://user-agent-string.info';

    /**
     * Path to store data file downloads to.
     * @type string|null
     */
    protected $cache_dir = null;

    /**
     * Array of parsed UAS data.
     * @type array|null
     */
    protected $data = null;

    /**
     * Constructor.
     * @param string $cacheDirectory Cache directory for data downloads
     * @param integer $updateInterval Allowed age of the cache file.
     * @param bool $debug Whether to emit debug info.
     * @param bool $doDownloads Whether to allow data downloads.
     */
    public function __construct($cacheDirectory = null, $updateInterval = null, $debug = false, $doDownloads = true)
    {
        if ($cacheDirectory) {
            $this->SetCacheDir($cacheDirectory);
        }
        if ($updateInterval) {
            $this->updateInterval = $updateInterval;
        }
        $this->debug = (boolean)$debug;
        $this->doDownloads = (boolean)$doDownloads;
    }

    /**
     * Output a time-stamped debug message if debugging is enabled
     * @param string $msg
     */
    protected function debug($msg)
    {
        if ($this->debug) {
            echo gmdate('Y-m-d H:i:s') . "\t$msg\n";
        }
    }

    /**
     * Parse the useragent string if given, otherwise parse the current user agent.
     * @param string $useragent user agent string
     * @return array
     */
    public function parse($useragent = null)
    {
        // Intialize some variables
        $browser_id = $os_id = null;
        $result = array();

        // Initialize the return value
        $result['typ'] = 'unknown';
        $result['ua_family'] = 'unknown';
        $result['ua_name'] = 'unknown';
        $result['ua_version'] = 'unknown';
        $result['ua_url'] = 'unknown';
        $result['ua_company'] = 'unknown';
        $result['ua_company_url'] = 'unknown';
        $result['ua_icon'] = 'unknown.png';
        $result['ua_info_url'] = 'unknown';
        $result['os_family'] = 'unknown';
        $result['os_name'] = 'unknown';
        $result['os_url'] = 'unknown';
        $result['os_company'] = 'unknown';
        $result['os_company_url'] = 'unknown';
        $result['os_icon'] = 'unknown.png';

        // If no user agent is supplied process the one from the server vars
        if (!isset($useragent) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        }

        // If we haven't loaded the data yet, do it now
        if (!$this->data) {
            $this->data = $this->loadData();
        }

        // We have no data or no valid user agent, just return the default data
        if (!$this->data || !isset($useragent)) {
            return $result;
        }

        // Crawler
        foreach ($this->data['robots'] as $test) {
            if ($test[0] == $useragent) {
                $result['typ'] = 'Robot';
                if ($test[1]) {
                    $result['ua_family'] = $test[1];
                }
                if ($test[2]) {
                    $result['ua_name'] = $test[2];
                }
                if ($test[3]) {
                    $result['ua_url'] = $test[3];
                }
                if ($test[4]) {
                    $result['ua_company'] = $test[4];
                }
                if ($test[5]) {
                    $result['ua_company_url'] = $test[5];
                }
                if ($test[6]) {
                    $result['ua_icon'] = $test[6];
                }
                if ($test[7]) { // OS set
                    $os_data = $this->data['os'][$test[7]];
                    if ($os_data[0]) {
                        $result['os_family'] = $os_data[0];
                    }
                    if ($os_data[1]) {
                        $result['os_name'] = $os_data[1];
                    }
                    if ($os_data[2]) {
                        $result['os_url'] = $os_data[2];
                    }
                    if ($os_data[3]) {
                        $result['os_company'] = $os_data[3];
                    }
                    if ($os_data[4]) {
                        $result['os_company_url'] = $os_data[4];
                    }
                    if ($os_data[5]) {
                        $result['os_icon'] = $os_data[5];
                    }
                }
                if ($test[8]) {
                    $result['ua_info_url'] = self::$info_url . $test[8];
                }
                return $result;
            }
        }

        // Find a browser based on the regex
        foreach ($this->data['browser_reg'] as $test) {
            if (@preg_match($test[0], $useragent, $info)) { // $info may contain version
                $browser_id = $test[1];
                break;
            }
        }

        // A valid browser was found
        if ($browser_id) { // Browser detail
            $browser_data = $this->data['browser'][$browser_id];
            if ($this->data['browser_type'][$browser_data[0]][0]) {
                $result['typ'] = $this->data['browser_type'][$browser_data[0]][0];
            }
            if (isset($info[1])) {
                $result['ua_version'] = $info[1];
            }
            if ($browser_data[1]) {
                $result['ua_family'] = $browser_data[1];
            }
            if ($browser_data[1]) {
                $result['ua_name'] = $browser_data[1] . (isset($info[1]) ? ' ' . $info[1] : '');
            }
            if ($browser_data[2]) {
                $result['ua_url'] = $browser_data[2];
            }
            if ($browser_data[3]) {
                $result['ua_company'] = $browser_data[3];
            }
            if ($browser_data[4]) {
                $result['ua_company_url'] = $browser_data[4];
            }
            if ($browser_data[5]) {
                $result['ua_icon'] = $browser_data[5];
            }
            if ($browser_data[6]) {
                $result['ua_info_url'] = self::$info_url . $browser_data[6];
            }
        }

        // Browser OS, does this browser match contain a reference to an os?
        if (isset($this->data['browser_os'][$browser_id])) { // OS detail
            $os_id = $this->data['browser_os'][$browser_id][0]; // Get the OS id
            $os_data = $this->data['os'][$os_id];
            if ($os_data[0]) {
                $result['os_family'] = $os_data[0];
            }
            if ($os_data[1]) {
                $result['os_name'] = $os_data[1];
            }
            if ($os_data[2]) {
                $result['os_url'] = $os_data[2];
            }
            if ($os_data[3]) {
                $result['os_company'] = $os_data[3];
            }
            if ($os_data[4]) {
                $result['os_company_url'] = $os_data[4];
            }
            if ($os_data[5]) {
                $result['os_icon'] = $os_data[5];
            }
            return $result;
        }

        // Search for the OS
        foreach ($this->data['os_reg'] as $test) {
            if (@preg_match($test[0], $useragent)) {
                $os_id = $test[1];
                break;
            }
        }

        // A valid OS was found
        if ($os_id) { // OS detail
            $os_data = $this->data['os'][$os_id];
            if ($os_data[0]) {
                $result['os_family'] = $os_data[0];
            }
            if ($os_data[1]) {
                $result['os_name'] = $os_data[1];
            }
            if ($os_data[2]) {
                $result['os_url'] = $os_data[2];
            }
            if ($os_data[3]) {
                $result['os_company'] = $os_data[3];
            }
            if ($os_data[4]) {
                $result['os_company_url'] = $os_data[4];
            }
            if ($os_data[5]) {
                $result['os_icon'] = $os_data[5];
            }
        }
        return $result;
    }

    /**
     * Load agent data from the files.
     * Will download data if we don't have any.
     * @return boolean
     */
    protected function loadData()
    {
        if (!file_exists($this->cache_dir)) {
            $this->debug('Cache file not found');
            return false;
        }

        if (file_exists($this->cache_dir . '/cache.ini')) {
            $cacheIni = parse_ini_file($this->cache_dir . '/cache.ini');

            // Should we fetch new data because it is too old?
            if ($cacheIni['lastupdatestatus'] != '1' || $cacheIni['lastupdate'] < time() - $this->updateInterval) {
                if ($this->doDownloads) {
                    $this->downloadData();
                } else {
                    $this->debug('Downloads suppressed, using old data');
                }
            }
        } else {
            // Do a download even if downloads are disabled as otherwise we can't work at all
            if (!$this->doDownloads) {
                $this->debug('Data missing - Doing download even though downloads are suppressed');
            }
            $this->downloadData();
        }

        // We have file with data, parse and return it
        if (file_exists($this->cache_dir . '/uasdata.ini')) {
            return @parse_ini_file($this->cache_dir . '/uasdata.ini', true);
        } else {
            $this->debug('Data file not found');
        }
        return false;
    }

    /**
     * Download new data.
     * @param bool $force Whether to force a download even if we have a cached file
     * @return boolean
     */
    public function downloadData($force = false)
    {
        // by default status is failed
        $status = false;
        // support for one of curl or fopen wrappers is needed
        if (!ini_get('allow_url_fopen') && !function_exists('curl_init')) {
            $this->debug('Fopen wrappers and curl unavailable, cannot continue');
            trigger_error(
                'ERROR: function file_get_contents not allowed URL open. Update the datafile (uasdata.ini in Cache Dir) manually.'
            );
            return $status;
        }

        $cacheIni = array();
        if (file_exists($this->cache_dir . '/cache.ini')) {
            $cacheIni = parse_ini_file($this->cache_dir . '/cache.ini');
        }

        // Check the version on the server
        // If we are current, don't download again
        $ver = $this->getContents(self::$ver_url, $this->timeout);
        if (preg_match('/^[0-9]{8}-[0-9]{2}$/', $ver)) { //Should be a date and version string like '20130529-01'
            if (array_key_exists('localversion', $cacheIni)) {
                if ($ver <= $cacheIni['localversion']) { //Version on server is same as or older than what we already have
                    if ($force) {
                        $this->debug('Existing file is current, but forcing a download anyway.');
                    } else {
                        $this->debug('Download skipped, existing file is current.');
                        $status = true;
                        $this->writeCacheIni($ver, $status);
                        return $status;
                    }
                }
            }
        } else {
            $this->debug('Version string format mismatch.');
            $ver = 'none'; //Server gave us something unexpected
        }

        // Download the ini file
        $ini = $this->getContents(self::$ini_url, $this->timeout);
        if (!empty($ini)) {
            // Download the hash file
            $md5hash = $this->getContents(self::$md5_url, $this->timeout);
            if (!empty($md5hash)) {
                // Validate the hash, if okay store the new ini file
                if (md5($ini) == $md5hash) {
                    $written = @file_put_contents($this->cache_dir . '/uasdata.ini', $ini, LOCK_EX);
                    if ($written === false) {
                        $this->debug('Failed to write data file to ' . $this->cache_dir . '/uasdata.ini');
                    } else {
                        $status = true;
                    }
                } else {
                    $this->debug('Data file hash mismatch.');
                }
            } else {
                $this->debug('Failed to fetch hash file.');
            }
        } else {
            $this->debug('Failed to fetch data file.');
        }
        $this->writeCacheIni($ver, $status);
        return $status; //Return true on success
    }

    /**
     * Generate and write the cache.ini file in the cache directory
     * @param string $ver
     * @param string $status
     * @return bool
     */
    protected function writeCacheIni($ver, $status)
    {
        // Build a new cache file and store it
        $cacheIni = "; cache info for class UASparser - http://user-agent-string.info/download/UASparser\n";
        $cacheIni .= "[main]\n";
        $cacheIni .= "localversion = \"$ver\"\n";
        $cacheIni .= 'lastupdate = "' . time() . "\"\n";
        $cacheIni .= "lastupdatestatus = \"$status\"\n";
        $written = @file_put_contents($this->cache_dir . '/cache.ini', $cacheIni, LOCK_EX);
        if ($written === false) {
            $this->debug('Failed to write cache file to ' . $this->cache_dir . '/cache.ini');
            return false;
        }
        return true;
    }

    /**
     * Get the contents of a URL with a defined timeout.
     * The timeout is set high (5 minutes) as the site can be slow to respond
     * You shouldn't be doing this request interactively anyway!
     * @param string $url
     * @param int $timeout
     * @return string
     */
    protected function getContents($url, $timeout = 300)
    {
        $data = '';
        $starttime = microtime(true);
        // use fopen
        if (ini_get('allow_url_fopen')) {
            $fp = @fopen(
                $url,
                'rb',
                false,
                stream_context_create(
                    array(
                        'http' => array(
                            'timeout' => $timeout,
                            'header' => "Accept-Encoding: gzip\r\n"
                        )
                    )
                )
            );
            if (is_resource($fp)) {
                $data = stream_get_contents($fp);
                $res = stream_get_meta_data($fp);
                if (array_key_exists('wrapper_data', $res)) {
                    foreach ($res['wrapper_data'] as $d) {
                        if ($d == 'Content-Encoding: gzip') { //Data was compressed
                            $data = gzinflate(substr($data, 10, -8)); //Uncompress data
                            $this->debug('Successfully uncompressed data');
                            break;
                        }
                    }
                }
                fclose($fp);
                if (empty($data)) {
                    if ($this->debug) {
                        if ($res['timed_out']) {
                            $this->debug('Fetching URL failed due to timeout: ' . $url);
                        } else {
                            $this->debug('Fetching URL failed: ' . $url);
                        }
                    }
                    $data = '';
                } else {
                    $this->debug(
                        'Fetching URL with fopen succeeded: ' . $url . '. ' . strlen($data) . ' bytes in ' . (microtime(
                                true
                            ) - $starttime) . ' sec.'
                    );
                }
            } else {
                $this->debug('Opening URL failed: ' . $url);
            }
        } elseif (function_exists('curl_init')) {
            // Fall back to curl
            $ch = curl_init($url);
            curl_setopt_array(
                $ch,
                array(
                    CURLOPT_TIMEOUT => $timeout,
                    CURLOPT_CONNECTTIMEOUT => $timeout,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => 'gzip'
                )
            );
            $data = curl_exec($ch);
            if ($data !== false and curl_errno($ch) == 0) {
                $this->debug(
                    'Fetching URL with curl succeeded: ' . $url . '. ' . strlen($data) . ' bytes in ' . (microtime(
                            true
                        ) - $starttime) . ' sec.'
                );
            } else {
                $this->debug('Opening URL with curl failed: ' . $url . ' ' . curl_error($ch));
                $data = '';
            }
            curl_close($ch);
        } else {
            trigger_error('Could not fetch UAS data; neither fopen nor curl are available.', E_USER_ERROR);
        }
        return $data;
    }

    /**
     * Set the cache directory.
     * @param string
     * @return bool
     */
    public function setCacheDir($cache_dir)
    {
        $this->debug('Setting cache dir to ' . $cache_dir);
        // The directory does not exist at this moment, try to make it
        if (!file_exists($cache_dir)) {
            @mkdir($cache_dir, 0777, true);
        }

        // perform some extra checks
        if (!is_writable($cache_dir) || !is_dir($cache_dir)) {
            $this->debug('Cache dir(' . $cache_dir . ') is not a directory or not writable');
            return false;
        }

        // store the cache dir
        $cache_dir = realpath($cache_dir);
        $this->cache_dir = $cache_dir;
        return true;
    }

    /**
     * Get the cache directory
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cache_dir;
    }

    /**
     * Clear the cache files
     */
    public function clearCache()
    {
        @unlink($this->cache_dir . '/cache.ini');
        @unlink($this->cache_dir . '/uasdata.ini');
        $this->debug('Cleared cache.');
    }

    /**
     * Clear internal data store
     */
    public function clearData()
    {
        $this->data = null;
        $this->debug('Cleared data.');
    }

    /**
     * Get whether downloads are allowed.
     * @return bool
     */
    public function getDoDownloads()
    {
        return $this->doDownloads;
    }

    /**
     * Set whether downloads are allowed.
     * @param $doDownloads
     */
    public function setDoDownloads($doDownloads) {
        $this->doDownloads = (boolean)$doDownloads;
    }
}
