<?php
$network_check = unserialize(SOCIAL_BOOSTER_NETWORKS);
?>

<div class="sb-setup-guide">
    <div class="header">
        <h4><img v-bind:src="getPluginDirUrl + 'admin/app/images/clipboard.png'" alt="icon"> <?php _e('Setup guide','rx-sb'); ?></h4>
        <a href="https://rextheme.com/docs/how-to-setup-social-media-accounts-with-social-booster/" target="_blank" class="btn-default"><?php _e('Documentation','rx-sb'); ?></a>
    </div>

    <div class="guide-body">
        <b-card no-body>
            <b-tabs card>

                <b-tab class="single-tab" title="facebook" active>
                    <div class="guide-wrapper">
                        <ol>
                            <li v-if="site_protocol === 'https:'" >Your server is secured and running with https.</li>
                            <li v-if="site_protocol === 'http:'" >https:// required to configure facebook. Facebook doesn't allow insecured url. So move your server form http to https or try loading your site using https. Check <a href="https://developers.facebook.com/docs/facebook-login/review/requirements/">https://developers.facebook.com/docs/facebook-login/review/requirements/</a> for more information.</li>
                            <li>Go to <a href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a> and click on "+ Add a New App". If you already have an app, follow step 3.</li>
                            <li>Go to Settings->Platform and put App Domain: <span class="copy-text" id="click-copy-host" @click.stop.prevent="clickToCopy"  @mouseleave="mouseLeave">{{site_hostname}}</span></li>
                            <li>Put valid Privacy Policy URL and Terms of Service URL on App details section</li>
                            <li>Copy your facebook app id and app secret from app profile section</li>
                            <li>Go to facebook setup form and paste app id and app secret</li>
                            <li>Authorization on personal profile is removed from facebook. You can only authorize on your page or group</li>
                            <li>Select page or group and paste page or group id on platform id field of facebook setup form</li>
                            <li>Now you are ready to authorize</li>
                        </ol>
                    </div>
                </b-tab>

                <b-tab class="single-tab" title="twitter">
                    <div class="guide-wrapper">
                        <ol>
                            <li>If you have any existing application, go to <a href="https://developer.twitter.com/en/apps" target="_blank">https://developer.twitter.com/en/apps</a> and open the app</li>
                            <li>Otherwise you have to apply for a developer account and create new app</li>
                            <li>Go to your app details page and add website url: <span class="copy-text" id="click-copy-url" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_url}}</span> and callback url: <span class="copy-text" id="click-copy-call-url" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}</span></li>
                            <li>Go to "Keys and token" section and copy consumer key and consumer secret</li>
                            <li>Paste them on twitter setup form and you are ready to authorize</li>
                        </ol>
                    </div>
                </b-tab>

                <b-tab class="single-tab" title="tumblr">
                    <div class="guide-wrapper">
                        <ol>
                            <li> Go to <a href="https://www.tumblr.com/oauth/apps" target="_blank">https://www.tumblr.com/oauth/apps</a> and click on "+ Register application"</li>
                            <li>If you already have an app just open that app</li>
                            <li>Edit your app and add application website: <span class="copy-text" id="click-copy-url-tb" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_url}}</span> and default callback url: <span class="copy-text" id="click-copy-call-url-tb" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}</span></li>
                            <li>Go to your tumblr account setting and select the blog you want to use</li>
                            <li>You will see tumblr url under the username section. Copy blogs tumblr url </li>
                            <li>Copy consumer key and consumer secret</li>
                            <li>Paste them on tumblr setup form and you are ready to authorize</li>
                        </ol>
                    </div>
                </b-tab>

                <b-tab class="single-tab" title="Pinterest">
                    <div class="guide-wrapper">
                        <ol>
                          <li v-if="site_protocol === 'https:'">Your site is running with https and you are ready to go. </li>
                          <li v-if="site_protocol === 'http:'">Your site is not running with https and you should add ssl before authorization. </li>
                          <li>If you have any existing application, go to <a href="https://developers.pinterest.com/apps/" target="_blank">https://developers.pinterest.com/apps/</a> and open the app</li>
                          <li>Otherwise you may create new app</li>
                          <li>Go to your app details page and add website url: <span class="copy-text" id="click-copy-url-pt" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_url}}</span> and callback url: <span class="copy-text" id="click-copy-call-url-pt" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}admin.php?page=rex-social-booster#/</span></li>
                          <li>Go to "app id and app secret" section and copy.</li>
                          <li>Paste them on pinterest setup form</li>
                          <li>Go to your pinterest account and select the board you want to pin. You will find board name on the url.</li>
                          <li>Copy board name and paste on pinterest setup form</li>
                          <li>You are ready to authorize</li>
                        </ol>
                    </div>
                </b-tab>
                <?php
                  if(array_key_exists('linkedin', $network_check)) {
                    ?>
                    <b-tab class="single-tab" title="linkedin">
                        <div class="guide-wrapper">
                            <ol>
                              <li v-if="site_protocol === 'https:'">Your site is running with https and you are ready to go. </li>
                              <li v-if="site_protocol === 'http:'">Your site is not running with https and you should add ssl before authorization. </li>
                              <li>LinkedIn does not support TLS 1.0. You must use TLS 1.1 or 1.2 when calling LinkedIn APIs. All API requests to api.linkedin.com must be made over HTTPS. Calls made over HTTP will fail.</li>
                              <li>If you are just getting started, <a href="https://www.linkedin.com/developer/apps/new?csrfToken=ajax%3A8674117952230020505" data-linktype="external" target="_blank">create a new application</a>.</li>
                              <li>If you have an existing application, <a href="https://www.linkedin.com/developers/apps" data-linktype="external" target="_blank">select it</a> to modify its settings.</li>
                              <li>click the "Auth" link in the navigation to view your application's credentials and configure a callback URL <span class="copy-text" id="click-copy-call-url-ld" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}</span> to your server.</li>
                              <li>Copy Client Id & Client Secret from your app and paste here.</li>
                              <li>You are ready to authorize</li>
                            </ol>
                        </div>
                    </b-tab>
                    <?php
                  }
                  if(array_key_exists('reddit', $network_check)) {
                    ?>
                    <b-tab class="single-tab" title="reddit">
                        <div class="guide-wrapper">
                            <ol>
                              <li>If you are just getting started, <a href="https://www.reddit.com/prefs/apps" data-linktype="external" target="_blank">create a new application</a>.</li>
                              <li>If you have an existing application, select it to modify its settings.</li>
                              <li>Add callback URL <span class="copy-text" id="click-copy-call-url-rd" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}</span> to your app.</li>
                              <li>Copy Client Id & Client Secret from your app and paste here.</li>
                              <li>You are ready to authorize</li>
                            </ol>
                        </div>
                    </b-tab>
                    <?php
                  }
                ?>
            </b-tabs>
        </b-card>

        <input type="hidden" :value="site_hostname" id="copied-host">
        <b-tooltip target="click-copy-host" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>

        <input type="hidden" :value="site_url" id="copied-url">
        <b-tooltip target="click-copy-url" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>
        <b-tooltip target="click-copy-url-tb" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>

        <input type="hidden" :value="site_callback" id="copied-call-url">
        <b-tooltip target="click-copy-call-url" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>

        <b-tooltip target="click-copy-call-url-tb" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>
        <b-tooltip target="click-copy-url-pt" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>
        <b-tooltip target="click-copy-call-url-pt" ref="tooltip">
            {{copyMsg}}
        </b-tooltip>
        <?php
          if(array_key_exists('linkedin', $network_check)) {
            ?>
            <b-tooltip target="click-copy-call-url-ld" ref="tooltip">
                {{copyMsg}}
            </b-tooltip>
            <?php
          }
          if(array_key_exists('reddit', $network_check)) {
            ?>
            <b-tooltip target="click-copy-call-url-rd" ref="tooltip">
                {{copyMsg}}
            </b-tooltip>
            <?php
          }
        ?>
        <!--<PageLoader></PageLoader>-->
    </div>
</div>
