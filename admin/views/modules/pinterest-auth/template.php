<div class="networks-content">
    <div class="fb-auth-form">

        <form v-on:submit="sub" action="#" method="post">
            <div class="form-wrapper">
                <div class="form-wrapper">
                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Client ID</h6>
                        </div>
                        <div class="sb-form">
                            <input v-model="client_id" type="text" name="client_id" placeholder="Enter Your Client ID">
                        </div>
                    </div>

                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Client Secret</h6>
                        </div>
                        <div class="sb-form">
                            <input v-model="client_secret" type="password" name="client_secret" placeholder="Enter Your Client Secret">
                        </div>
                    </div>

                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Board name:</h6>
                        </div>
                        <div class="sb-form">
                            <input v-model="board_name" type="text" name="board_name" placeholder="Enter board name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="guide-wrapper">
                <ol>
                    <li v-if="site_protocol === 'https:'">Your site is running with https and you are ready to go. </li>
                    <li v-if="site_protocol === 'http:'">Your site is not running with https and you should add ssl before authorization. </li>
                    <li>If you have any existing application, go to <a href="https://developers.pinterest.com/apps/" target="_blank">https://developers.pinterest.com/apps/</a> and open the app</li>
                    <li>Otherwise you may create new app</li>
                    <li>Go to your app details page and add website url: <span class="copy-text" id="click-copy-url" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_url}}</span> and callback url: <span class="copy-text" id="click-copy-call-url" @click.stop.prevent="clickToCopy" @mouseleave="mouseLeave">{{site_callback}}</span></li>
                    <li>Go to "app id and app secret" section and copy.</li>
                    <li>Paste them on pinterest setup form</li>
                    <li>Go to your pinterest account and select the board you want to pin. You will find board name on the url.</li>
                    <li>Copy board name and paste on pinterest setup form</li>
                    <li>You are ready to authorize</li>
                </ol>
            </div>
            <b-button variant="primary" class="fb-info-submit" type="submit">
                <?php _e('Save','rx-sb'); ?>
                <b-spinner small label="Small Spinner" v-if="authStart"></b-spinner>
            </b-button>
        </form>
    </div>
</div>
