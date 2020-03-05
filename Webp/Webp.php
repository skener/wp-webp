<?php

namespace Webp;

use DOMDocument;

/**
 * Class Webp
 * @package Webp
 */
class Webp
{

    public function __construct()
    {

        add_action('enqueue_scripts', $this->initScripts());
        add_filter('the_content', [$this, 'addCustomAttributes']);

    }

    public function activate()
    {

        $this->custom_post_type();

        flush_rewrite_rules();
    }

    public function deactivate()
    {

        flush_rewrite_rules();
    }

    /**
     * Init custom js scripts
     */
    public function initScripts()
    {
        wp_register_script('webp-lazy-load-js', $src = plugins_url('/webp_plugin/js/webp-lazy-load.js'), array('jquery'), microtime(), true);
        wp_enqueue_script('webp-lazy-load-js');
    }

    /**
     * @param $the_content
     * @return string
     */
    public function addCustomAttributes($the_content)
    {

        libxml_use_internal_errors(true);
        $post = new DOMDocument();
        $post->loadHTML('<?xml encoding="utf-8" ?>' . $the_content);
        $imgs = $post->getElementsByTagName('img');

        // Iterate each img tag
        foreach ($imgs as $img) {
            if ($img->hasAttribute('data-src')) continue;
            if ($img->parentNode->tagName == 'noscript') continue;
            $clone = $img->cloneNode();
            $src = $img->getAttribute('src');
            $img->removeAttribute('src');
            $img->setAttribute('data-src', $src);
            $srcset = $img->getAttribute('srcset');
            $img->removeAttribute('srcset');
            if (!empty($srcset)) {
                $img->setAttribute('data-srcset', $srcset);
            }
            $imgClass = $img->getAttribute('class');
            $img->setAttribute('class', $imgClass . ' lazy');
            $no_script = $post->createElement('noscript');
            $no_script->appendChild($clone);
            $img->parentNode->insertBefore($no_script, $img);
        }

        return $post->saveHTML();

    }

}
