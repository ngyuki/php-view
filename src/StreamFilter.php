<?php
namespace App\PhpRenderer;

/**
 * @property resource $stream
 */
class StreamFilter extends \php_user_filter
{
    private static $registered = [];

    protected static $escape;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $source = '';

    public function __construct($name = 'app-view')
    {
        if (isset(self::$registered[$name]) === false) {
            stream_filter_register($name, static::class);
            self::$registered[$name] = $this;
        } else if (self::$registered[$name] !== $this) {
            throw new \LogicException("filter $name always registered");
        }

        $this->name = $name;

        if (static::$escape === null) {
            static::$escape = function ($str) {
                return htmlspecialchars($str);
            };
        }
    }

    public function generate($file)
    {
        return "php://filter/read={$this->name}/resource={$file}";
    }

    public function onCreate()
    {
        return true;
    }

    public function onClose()
    {
        // none
    }

    public function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $this->source .= $bucket->data;
            $consumed += $bucket->datalen;
        }

        if ($closing) {
            $bucket = stream_bucket_new($this->stream, $this->rewrite($this->source));
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }

    public function rewrite($source)
    {
        $tokens = token_get_all($source);

        $flag = false;
        $output = '';

        foreach ($tokens as $token) {
            if (is_array($token)) {
                list ($id, $code) = $token;

                if ($id === T_OPEN_TAG_WITH_ECHO) {
                    $flag = true;
                    $code = $code . get_class($this) . '::escape(';
                } else {
                    if ($flag && $id === T_CLOSE_TAG) {
                        $flag = false;
                        $code = ')' . $code;
                    }
                }

                $output .= $code;
            } else {
                $output .= $token;
            }
        }

        return $output;
    }

    public static function escape($str)
    {
        return (static::$escape)($str);
    }
}
