<div class="networks-content">
  <div class="fb-auth-form">

    <form v-on:submit="sub" action="#" method="post">
        <div class="form-wrapper">
            <div class="form-wrapper">
                <div class="sb-form-group">
                    <div class="form-title">
                        <h6 class="title">Consumer key</h6>
                    </div>
                    <div class="sb-form">
                        <input v-model="consumer_key" type="text" name="consumer_key" placeholder="Enter Your Consumer key">
                    </div>
                </div>

                <div class="sb-form-group">
                    <div class="form-title">
                        <h6 class="title">Consumer Secret</h6>
                    </div>
                    <div class="sb-form">
                        <input v-model="consumer_secret" type="password" name="consumer_secret" placeholder="Enter Your Consumer Secret">
                    </div>
                </div>
            </div>
        </div>
        <div class="guide-wrapper">
            <ol>
                <li>If you have any existing application, go to <a href="https://developer.twitter.com/en/apps" target="_blank">https://developer.twitter.com/en/apps</a> and open the app</li>
                <li>Otherwise you have to apply for a developer account and create new app</li>
                <li>Go to your app details page and add website url: <span class="copy-text" id="click-copy-url" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_url}}</span> and callback url: <span class="copy-text" id="click-copy-call-url" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}</span></li>
                <li>Go to "Keys and token" section and copy consumer key and consumer secret</li>
                <li>Paste them on twitter setup form and you are ready to authorize</li>
            </ol>
        </div>
        <b-button variant="primary" class="fb-info-submit" type="submit">
            <?php _e('Save','rx-sb'); ?>
            <b-spinner small label="Small Spinner" v-if="authStart"></b-spinner>
        </b-button>
    </form>
  </div>
</div>
