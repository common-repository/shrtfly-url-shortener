<?php
if (isset($_REQUEST['action']) && is_callable([ShrtFly::instance(), $_REQUEST['action']]))
	ShrtFly::instance()->{$_REQUEST['action']}();

$data = array_merge([
	'token' => '',
	'domains' => '',
	'patterns' => ''
], (array)json_decode(@get_option('ShrtFly') ?: '', true));
?>
<style>
	#ShrtFly-template {
		margin: 20px 20px 20px 0;
		background: white;
		padding: 30px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		border: 1px solid #ddd;
	}

	#ShrtFly-template h1 {
		margin-top: 0;
	}

	#ShrtFly-template .w100 {
		width: 100%;
	}

	#ShrtFly-template .notice {
		line-height: 40px;
		margin: 0;
	}
</style>
<div id="ShrtFly-template">
	<h1>Configure ShrtFly</h1>

	<?php if(isset($_POST['action'])) : ?>
		<p class="notice notice-success">
			Save data successfully
		</p>
	<?php endif; ?>

	<form id="ShrtFly-form" method="POST" action="<?= $_SERVER['REQUEST_URI']; ?>">
		<input type="hidden" name="action" value="saveData">
		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="ShrtFly-template-token">Token key: Important</label><br>
					<a href="https://shrtfly.com/member/tools/quick">Find Your Token</a>
				</th>
				<td>
					<input type="text" id="ShrtFly-template-token" autocomplete="off"
					       class="w100" name="token" value="<?= $data['token']; ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label for="ShrtFly-template-domains">Domain: Important</label>
					<p>Put the links here that you do not want to shorten</p>
					<p>यहां पर उन लिंक को डाले जिनको आप शॉर्ट नहीं करना चाहते हैं</p>
					<p>For example:<br/>
					<code>yourwebsite.com</code>
					<p><em>Each domain writes 1 line</em></p>
				</th>
				<td>
					<textarea type="text" id="ShrtFly-template-domains" rows="5"
					          class="w100" name="domains"><?= $data['domains']; ?></textarea>
				</td>
			</tr>
			<tr>
				<th>
					<label for="ShrtFly-template-patterns">Patterns: Advance Users Only (Optional) </label>
					<p>Encrypts all paths by regular expression. Use spaces to separate the expression and the modifier</p>
					<p>For example: <br/>
					<code>link</code><br/>
                    <code>^link$ i</code>
					</p>
					<p><em>Each expression writes 1 line</em></p>
				</th>
				<td>
					<textarea type="text" id="ShrtFly-template-patterns" rows="5"
					          class="w100" name="patterns"><?= $data['patterns']; ?></textarea>
				</td>
			</tr>
			</tbody>
		</table>

		<button class="button button-primary" type="submit">Save</button>
	</form>
</div>


<script>

    var ShrtFly_url = 'http://shrtfly.com/';
    var ShrtFly_api_token = 'c8567daab72b8c4ae7bd191201beb5c4fdcd2207';
    var ShrtFly_advert = 2;
    var ShrtFly_domains = ['userscloud.com', 'drive.google.com', 'docs.google.com', 'www.mediafire.com', 'zippyshare.com', 'uploadocean.com', 'openload.co'];

    function ShrtFly_get_url(e) {
        var n = document.createElement("a");
        return n.href = e, n
    }

    function ShrtFly_get_host_name(e) {
        var n;
        return void 0 === e || null === e || "" === e || e.match(/^\#/) ? "" : -1 === (e = ShrtFly_get_url(e)).href.search(/^http[s]?:\/\//) ? "" : (n = e.href.split("/")[2], (n = n.split(":")[0]).toLowerCase())
    }

    function ShrtFly_base64_encode(e) {
        return btoa(encodeURIComponent(e).replace(/%([0-9A-F]{2})/g, function(e, n) {
            return String.fromCharCode("0x" + n)
        }))
    }
    document.addEventListener("DOMContentLoaded", function(e) {
        if ("undefined" != typeof ShrtFly_url && "undefined" != typeof ShrtFly_api_token) {
            var n = 1;
            "undefined" != typeof ShrtFly_advert && (2 == ShrtFly_advert && (n = 2), 0 == ShrtFly_advert && (n = 0));
            var l = document.getElementsByTagName("a");
            if ("undefined" == typeof ShrtFly_domains)
                if ("undefined" == typeof ShrtFly_exclude_domains);
                else
                    for (o = 0; o < l.length; o++) {
                        var t = ShrtFly_get_host_name(l[o].getAttribute("href"));
                        t.length > 0 && -1 === ShrtFly_exclude_domains.indexOf(t) ? l[o].href = ShrtFly_url + "full/?api=" + encodeURIComponent(ShrtFly_api_token) + "&url=" + ShrtFly_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n) : "magnet:" === l[o].protocol && (l[o].href = ShrtFly_url + "full/?api=" + encodeURIComponent(ShrtFly_api_token) + "&url=" + ShrtFly_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n))
                    } else
                for (var o = 0; o < l.length; o++)(t = ShrtFly_get_host_name(l[o].getAttribute("href"))).length > 0 && ShrtFly_domains.indexOf(t) > -1 ? l[o].href = ShrtFly_url + "full/?api=" + encodeURIComponent(ShrtFly_api_token) + "&url=" + ShrtFly_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n) : "magnet:" === l[o].protocol && (l[o].href = ShrtFly_url + "full/?api=" + encodeURIComponent(ShrtFly_api_token) + "&url=" + ShrtFly_base64_encode(l[o].href) + "&type=" + encodeURIComponent(n))
        }
    });
</script>
