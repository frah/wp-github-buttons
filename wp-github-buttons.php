<?php
/*
Plugin Name: GitHub Buttons
Plugin URI: 
Description: Insert GitHub buttons(http://ghbtns.com) to WordPress post.
Author: Atsushi OHNO
Author URI: http://tokcs.com/
Version: 0.1
License: MIT Open Source License - http://www.opensource.org/licenses/mit-license.php
*/

/* LICENSE OF THE TWEETSTER PLUGIN

Copyright (c) 2012 Atsushi OHNO.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

/**
 * Generate GitHub button code
 * @param string $user GitHub username
 * @param string $type Button type
 * @param string $repo GitHub repository name
 * @param string $count Is show the wathers or forks count
 * @param string $size Button size
 * @return string GitHub button code
 */
function gen_github_button($user, $type, $repo = '', $count = '', $size = '') {
    $ret = '';
    if ($type === 'follow') {
        $ret .= '<iframe src="http://ghbtns.com/github-btn.html';
        $ret .= '?user='.$user;
        $ret .= '&type=follow';
        $ret .= ($count === 'true')?'&count=true':'';
        $ret .= ($size === 'large')?'&size=large"':'"';
        $ret .= ' allowtransparency="true" frameborder="0" scrolling="0"';
        $ret .= ' height="'.($size === 'large')?'30':'20'.'px"';
        $ret .= '></iframe>';
    } else {
        $ret .= '<iframe src="http://ghbtns.com/github-btn.html';
        $ret .= '?user='.$user;
        $ret .= '&repo='.$repo;
        $ret .= '&type='.$type;
        $ret .= ($count === 'true')?'&count=true':'';
        $ret .= ($size === 'large')?'&size=large"':'"';
        $ret .= ' allowtransparency="true" frameborder="0" scrolling="0"';
        $ret .= ' height="'.($size === 'large')?'30':'20'.'px"';
        $ret .= '></iframe>';
    }
    
    return $ret;
}

/**
 * Shortcode handler (github/gh)
 * @param array $atts Attributes
 * @param mixed $content
 * @return 
 */
function handle_github_buttons_sc_github($atts, $content = null) {
    extract(shortcode_atts(array(
        'user' => null,
        'repo' => null,
        'type' => 'fork',
        'count' => '',
        'size' => ''
    ), $atts));
    
    if (!$user) {
        if ($content) {
            if (preg_match('/\s*https?:\/\/github\.com\/([^\/]+)(\/(.+))?/', $content, $m)) {
                $user = $m[1];
                $repo = $m[3];
            }
        }
    }
    
    if ($user && !$repo) {
        if ($type === 'follow') {
            return gen_github_button($user, $type);
        } else {
            return $content;
        }
    }
    
    return $user ? gen_github_button($user, $type, $repo, $count, $size):$content;
}

/**
 * Init the plugin
 */
function handle_github_buttons_init() {
    add_shortcode('github', 'handle_github_buttons_sc_github');
    add_shortcode('gh', 'handle_github_buttons_sc_github');
}

add_action('init', 'handle_github_buttons_init');
?>