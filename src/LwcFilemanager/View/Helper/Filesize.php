<?php
namespace LwcFilemanager\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Filesize extends AbstractHelper
{

    /**
     *
     * @var array
     */
    protected $prefixes = array(
        '',
        'k',
        'M',
        'G',
        'T'
    );

    /**
     *
     * @param int $size
     *            Size in bytes
     * @param int $precision
     *            Presicion of result (default 2)
     * @return string Transformed size
     */
    public function __invoke($size, $precision = 2)
    {
        $result = $size;
        $index = 0;
        while ($result > 1024 && $index < count($this->prefixes)) {
            $result = $result / 1024;
            $index ++;
        }
        return sprintf('%1.' . $precision . 'f %sB', $result, $this->prefixes[$index]);
    }
}