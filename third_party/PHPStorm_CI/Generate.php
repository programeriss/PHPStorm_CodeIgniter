<?php

/**
 * ------------- DO NOT UPLOAD THIS FILE TO LIVE SERVER ---------------------
 * Class Generate
 */
class Generate
{
    /**
     * @var string 
     */
    private $path = './templates/PHPStorm_CI.txt';

    /**
     * @var false|string 
     */
    private $fileData;

    /**
     * Generate constructor.
     */
    public function __construct()
    {
        $this->fileData = file_get_contents($this->path);
    }

    /**
     * @param string $codename
     * @param string $path
     */
    public function upload(string $codename, string $path)
    {
        $list = $this->getList($path);

        $text = "";
        foreach ($list as $tmp) {
            $text .= " * @property " . $tmp['className'] . " $" . $tmp['lowerCaseClassName'] . " " . $tmp['className'] . PHP_EOL;
        }

        $this->fileData = @str_replace($codename, $text, $this->fileData);
    }

    /**
     * @param string $path
     * @return array
     */
    private function getList(string $path): array
    {
        $result = [];
        $globs = @glob($path . '*.php');

        foreach ($globs as $glob) {
            $tmp = @str_replace($path, '', $glob);
            $tmp = @str_replace('.php', '', $tmp);

            array_push($result, ['className' => $tmp, 'lowerCaseClassName' => strtolower($tmp)]);
        }

        return $result;
    }

    public function save()
    {
        @unlink('PHPStorm_CI_CC.php');
        file_put_contents('PHPStorm_CI.php', $this->fileData);
        echo "File has been updated successfully!";
    }
}

$generate = new Generate();
$generate->upload('[#MODELS#]', '../../models/');
$generate->upload('[#LIBRARIES#]', '../../libraries/');
$generate->save();
