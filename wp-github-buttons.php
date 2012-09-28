<?php
/*
Plugin Name: GitHub Buttons
Plugin URI: https://github.com/frah/wp-github-buttons
Description: Insert GitHub buttons(http://ghbtns.com) to WordPress post.
Author: Atsushi OHNO
Author URI: http://tokcs.com/
Version: 0.3
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

$github_buttons_plugin = new GitHub_Buttons();

class GitHub_Buttons {
    var $op = array(
        'url'   => 'github_buttons_url',
        'type'  => 'github_buttons_type',
        'count' => 'github_buttons_count',
        'size'  => 'github_buttons_size'
    );
    var $def = array(
        'url'   => 'http://ghbtns.com',
        'type'  => 'fork',
        'count' => '',
        'size'  => ''
    );
    var $type = array(
        'Fork'   => 'fork',
        'Watch'  => 'watch',
        'Follow' => 'follow'
    );
    var $count = array(
        'No'  => '',
        'Yes' => 'true'
    );
    var $size = array(
        'Nomal' => '',
        'Large' => 'large'
    );

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array(&$this, 'handle_init'));
        add_action('admin_menu', array(&$this, 'handle_menu'));
    }

    /**
     * Init the plugin
     */
    public function handle_init() {
        add_shortcode('github', array(&$this, 'handle_sc_github'));
        add_shortcode('gh', array(&$this, 'handle_sc_github'));
    }

    /**
     * Get options from database
     * @param string $key Option key
     * @return string Option string
     */
    private function get_option($key) {
        return (array_key_exists($key, $this->op))?get_option($this->op[$key], $this->def[$key]):'';
    }

    /**
     * Generate GitHub button code
     * @param string $user GitHub username
     * @param string $type Button type
     * @param string $repo GitHub repository name
     * @param string $count Is show the wathers or forks count
     * @param string $size Button size
     * @return string GitHub button code
     */
    public function gen_github_button($user, $type, $repo = '', $count = '', $size = '') {
        $url = $this->get_option('url');
        $ret = '';

        $ret .= '<iframe src="';
        $ret .= $url.'/github-btn.html';
        $ret .= '?user='.$user;
        $ret .= '&type='.$type;
        if ($type !== 'follow') {
            $ret .= '&repo='.$repo;
        }
        $ret .= ($count === 'true')?'&count=true':'';
        $ret .= ($size === 'large')?'&size=large"':'"';
        $ret .= ' allowtransparency="true" frameborder="0" scrolling="0"';
        $ret .= ' height="'.($size === 'large')?'30':'20'.'px"';
        $ret .= '></iframe>';

        return $ret;
    }

    /**
     * Shortcode handler (github/gh)
     * @param array $atts Attributes
     * @param mixed $content
     * @return
     */
    public function handle_sc_github($atts, $content = null) {
        extract(shortcode_atts(array(
            'user'  => null,
            'repo'  => null,
            'type'  => $this->get_option('type'),
            'count' => $this->get_option('count'),
            'size'  => $this->get_option('size')
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
                return $this->gen_github_button($user, $type);
            } else {
                return $content;
            }
        }

        return $user ? $this->gen_github_button($user, $type, $repo, $count, $size):$content;
    }

    /**
     * Show options page
     */
    function handle_menu_html() {
        if (isset($_POST[$this->op['url']])) {
            check_admin_referer('update-options');
            foreach ($this->op as $v) {
                update_option($v, $_POST[$v]);
            }
            echo '<div class="updated"><p><strong>';
            echo __('Settings saved.');
            echo '</strong></p></div>';
        }

        /* Get existing settings */
        foreach ($this->op as $k => $v) {
            $$k = get_option($v, $this->def[$k]);
        }
?>
<div class="wrap">
<h2>GitHub Buttons Plugin Options</h2>
<form method="post" action="">
    <?php wp_nonce_field('update-options'); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><strong>Path to github-buttons</strong></th>
            <td>
                <input type="text" name="<?php echo $this->op['url']; ?>" value="<?php echo $url; ?>" />/github-btn.html
                <p>Default: http://ghbtns.com</p>
            </td>
        </tr>
    </table>
<h3>Default Settings</h3>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><strong>Button type</strong></th>
            <td>
                <select name="<?php echo $this->op['type']; ?>">
                <?php foreach ($this->type as $k => $v) { ?>
                    <option value="<?php echo $v; ?>"<?php echo ($type == $v)?' selected':''; ?>><?php echo $k; ?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><strong>Count</strong></th>
            <td>
                <select name="<?php echo $this->op['count']; ?>">
                <?php foreach ($this->count as $k => $v) { ?>
                    <option value="<?php echo $v; ?>"<?php echo ($count == $v)?' selected':''; ?>><?php echo $k; ?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><strong>Size</strong></th>
            <td>
                <select name="<?php echo $this->op['size']; ?>">
                <?php foreach ($this->size as $k => $v) { ?>
                    <option value="<?php echo $v; ?>"<?php echo ($size == $v)?' selected':''; ?>><?php echo $k; ?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
    </table>

    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="<?php echo implode(',', array_values($this->op)); ?>" />

    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
</div>
<?php
    }

    /**
     * Init options page
     */
    function handle_menu() {
        add_options_page('GitHub Buttons', 'GitHub Buttons', 8, __FILE__, array(&$this, 'handle_menu_html'));
    }
}
?>
