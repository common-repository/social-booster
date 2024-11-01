<div class="networks-content">
    <div class="fb-auth-form">

        <form v-on:submit="sub" action="#" method="post">

            <div class="form-wrapper">
                <div class="sb-form-group">
                    <div class="form-title">
                        <h6 class="title">Blog Url</h6>
                    </div>
                    <div class="sb-form">
                        <input v-model="blog_url" type="text" name="blog_url" placeholder="e.g. test.tumblr.com" required>
                    </div>
                </div>
                <div class="sb-form-group">
                    <div class="form-title">
                        <h6 class="title">Consumer key</h6>
                    </div>
                    <div class="sb-form">
                        <input v-model="consumer_key" type="text" name="consumer_key" placeholder="Enter Your Consumer key" required>
                    </div>
                </div>

                <div class="sb-form-group">
                    <div class="form-title">
                        <h6 class="title">Consumer Secret</h6>
                    </div>
                    <div class="sb-form">
                        <input v-model="consumer_secret" type="password" name="consumer_secret" placeholder="Enter Your Consumer Secret" required>

                    </div>
                </div>
            </div>

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

            <b-button variant="primary" class="fb-info-submit" type="submit">
                Save
                <b-spinner small label="Small Spinner" v-if="authStart"></b-spinner>
            </b-button>
            <!-- <a href="https://www.facebook.com/login/reauth.php?app_id=356021689781&signed_next=1&next=https%3A%2F%2Fwww.facebook.com%2Fv2.12%2Fdialog%2Foauth%3Fclient_id%3D356021689781%26auth_type%3Dreauthenticate%26redirect_uri%3Dhttps%253A%252F%252Fdeveloper.blog2social.com%252Fauth%252Fauth.php%26display%3Dpopup%26state%3D90488abf6c0a209c6b79dee7b3474044%26scope%3Demail%252Cpublic_profile%252Cmanage_pages%252Cpublish_pages%252Cpublish_to_groups%26ret%3Dlogin%26fbapp_pres%3D0%26fallback_redirect_uri%3D911d244f-d757-d266-cd65-7d75e30c1652&cancel_url=https%3A%2F%2Fdeveloper.blog2social.com%2Fauth%2Fauth.php%3Ferror%3Daccess_denied%26error_code%3D200%26error_description%3DPermissions%2Berror%26error_reason%3Duser_denied%26state%3D90488abf6c0a209c6b79dee7b3474044%23_%3D_&display=popup&locale=en_GB">Auth</a> -->
        </form>
    </div>
</div>
