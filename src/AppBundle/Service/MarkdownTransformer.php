<?php
/**
 * Created by PhpStorm.
 * User: lachlan
 * Date: 12/8/18
 * Time: 5:25 PM
 */

namespace AppBundle\Service;


use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class MarkdownTransformer
{
    /**
     * @var MarkdownInterface
     */
    private $markdown;

    public function __construct(MarkdownParserInterface $markdown)
    {
        $this->markdown = $markdown;
    }
    public function parse($str)
    {
        return $this->markdown->transformMarkdown($str);
    }
}