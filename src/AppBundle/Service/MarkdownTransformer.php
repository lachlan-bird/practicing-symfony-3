<?php
/**
 * Created by PhpStorm.
 * User: lachlan
 * Date: 12/8/18
 * Time: 5:25 PM
 */

namespace AppBundle\Service;


use Doctrine\Common\Cache\Cache;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class MarkdownTransformer
{
    /**
     * @var MarkdownInterface
     */
    private $markdown;
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(MarkdownParserInterface $markdown, Cache $cache)
    {
        $this->markdown = $markdown;
        $this->cache = $cache;
    }
    public function parse($str)
    {
        $key = md5($str);

        if ($this->cache->contains($key)) {
            return $this->cache->fetch($key);
        }

        sleep(1);
        $str =  $this->markdown->transformMarkdown($str);

        $this->cache->save($key, $str);

        return $str;
    }
}