<?php
/**
Plugin name: ShrtFly - URL Shortener
Plugin URI: http://shrtfly.com/
Description: ShrtFly is a free URL shortener which allows you to earn money for each visitor you bring your Shorted links.
Author: Surya Prakash
Author URI: https://shrtfly.com
License: GPL
Version: 1.0.0
 */

/**
 * Class ShrtFly
 * Singleton
 */

if ( !defined( 'ABSPATH' ) ) exit;

final class ShrtFly {
	private static $instance = null;

	public static function instance() {
		if ( static::$instance === null ) {
			static::$instance = new ShrtFly();
		}

		return static::$instance;
	}

	private function __construct() {
		// Add Menu
		if(isset($_REQUEST['action']) && is_callable($this->{$_REQUEST['action']})) {
			$this->{$_REQUEST['action']}();
		} else {
			$this->addMenu();
			$this->header();
		}
	}

	private function __clone() {
	}

	/**
	 * Alias of instance method
	 */
	public static function bootstrap() {
		static::instance();
	}

	protected function addMenu() {
		add_action( 'admin_menu', function () {
			add_submenu_page( 'options-general.php',
				'ShrtFly',
				'ShrtFly Settings',
				'administrator',
				'ShrtFly',
				[$this, 'adminTemplate']
			);
		} );
	}

	public function adminTemplate() {
		require __DIR__ . '/template.php';
	}

	public function saveData() {
		$data = $_REQUEST;
		unset($data['action'], $data['page']);
		update_option('ShrtFly', json_encode($data), true);
	}

	public function header() {
		add_action('wp_head', function() {
			?>
			<script type="text/javascript" id="ShrtFly-script">
(function(){
    function hashLink(link) {
        return 'http://shrtfly.com/full/?api=' + token + '&url=' + btoa(link) + '&type=1';
    }
    var data = <?= get_option('ShrtFly'); ?>,
        token = data.token || '';
    if (!token) return null;
    var domains = (data.domains || '').split(/\r?\n/).map(function(domain) {return domain.trim()}),
        patterns = (data.patterns || '').split(/\r?\n/).map(function(pattern) {
            pattern = pattern.trim().split(' ');
            if (!pattern[0]) return null;
            return new RegExp(pattern[0], (pattern[1] || ''));
        }).filter(function(pattern) {return pattern});

    domains.push('shrtfly.com');
    domains.push('www.shrtfly.com');

    setInterval(function() {
        var aTags = document.querySelectorAll('a:not(.hashed)');
        aTags.forEach(function(el) {
            var href = el.getAttribute('href') || '';
            if (!href || href[0] === '#' || href[0] === '!' || href.substr(0,11) === 'javascript:') return false;
            var hashed = false;
            if (domains.indexOf(el.hostname) === -1) {
                el.href = hashLink(el.href);
                hashed = true;
                return null;
            }
            
            patterns.forEach(function(pattern) {
                if(pattern.test(el.href)) {
                    el.href = hashLink(el.href);
                    hashed = true;
                }
            });

            if (hashed) el.classList.add('hashed');
        });
    }, 200);
})();
			</script>
			<?php
		});
	}
}

ShrtFly::bootstrap();
