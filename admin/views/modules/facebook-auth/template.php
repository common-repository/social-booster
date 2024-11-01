<div class="networks-content">
    <div class="fb-auth-form">
        <form v-on:submit="sub" action="#" method="post">
            <div class="sb-fb-forms">
                <div class="form-wrapper">

                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Application id </h6>
                        </div>
                        <div class="sb-form">
                            <input v-model="applicant_id" type="text" name="applicant_id" placeholder="Enter Your Application Id">
                        </div>
                    </div>

                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Application secret </h6>
                        </div>
                        <div class="sb-form">
                            <input v-model="applicant_secret" type="password" name="applicant_pass" placeholder="Enter Your App Secret">
                        </div>
                    </div>

                    <!-- Post Platform-->
                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Authorization platform </h6>
                        </div>
                        <div class="sb-form post-formate">
                            <input type="text" name="auth_platform" :value="authPlatform" placeholder="" readonly>
                        </div>
                    </div>
                    
                    <!-- Post Platform end-->
                    <div class="sb-form-group">
                        <div class="form-title">
                            <h6 class="title">Platform ID </h6>
                        </div>
                        <div class="sb-form">
                            <input v-model="authPlatformId" type="text" name="applicant_fb_id" placeholder="Enter Your platform ID">
                        </div>
                    </div>
                    <!--/form group-->
                </div>

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
            </div>
            <b-button variant="primary" class="fb-info-submit" type="submit">
                Save
                <b-spinner small label="Small Spinner" v-if="authStart"></b-spinner>
            </b-button>
        </form>
    </div>
</div>
