<div class="rex-sb-sharedposts">
    <div v-if="Object.keys(sharedPosts).length">
        <b-card v-for="post in sharedPosts" :key="post.id" no-body>
            <b-card-header header-tag="header" role="tab">
                <b-button block href="#" v-b-toggle="'shared-post-accordion-' + post.post_id" >
                    <div class="sb-recent-posts" id="sb-recent-posts">
                        <div class="single-post">
                            <div class="post">
                                <div class="post-image pos-relative">
                                    <img :src="post.img" alt="featured image"/>
                                </div>
                                <div class="post-content">
                                    <h1><a href="">{{post.post_title}}</a></h1>
                                    <p>{{post.excerpt}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <font-awesome-icon :icon="['fas', 'angle-down']"/>
                </b-button>
            </b-card-header>
            <b-collapse :id="'shared-post-accordion-' + post.post_id" accordion="rex-shared-post-accordion" role="tabpanel">
                <div class="shared-profile-wrapper ">
                    <div class="row">
                        <div class="col-lg-6"  v-for="post in post.posts" :key="post.id">
                            <div class="list-group">

                                <p v-if="postType=='schedule'" @click.prevent="showEditModal(post)"><span class="edit-schedule"><font-awesome-icon :icon="['fas', 'pen-square']"/></span></p>
                                <p v-if="postType=='schedule'" @click.prevent="confirmDelete(post.id)"><span class="trash-profile"><font-awesome-icon :icon="['far', 'trash-alt']"/></span></p>
                                <div class="social-icon">
                                    <span class="network-icon " :class="post.network" v-if="post.network === 'facebook'"><font-awesome-icon :icon="['fab', 'facebook-f']"/></span>
                                    <span class="network-icon " :class="post.network" v-if="post.network === 'twitter'"><font-awesome-icon :icon="['fab', 'twitter']"/></span>
                                    <span class="network-icon " :class="post.network" v-if="post.network === 'tumblr'"><font-awesome-icon :icon="['fab', 'tumblr']"/></span>
                                    <span class="network-icon " :class="post.network" v-if="post.network === 'linkedin'"><font-awesome-icon :icon="['fab', 'linkedin-in']"/></span>
                                    <span class="network-icon " :class="post.network" v-if="post.network === 'reddit'"><font-awesome-icon :icon="['fab', 'reddit-alien']"/></span>
                                    <span class="network-icon " :class="post.network" v-if="post.network === 'pinterest'"><font-awesome-icon :icon="['fab', 'pinterest-p']"/></span>
                                </div>
                                <div class="post-info">
                                    <b>{{post.network}}</b>
                                    <p>Profile: <span>{{post.prof_name}}</span></p>
                                    <div v-if="postType !='schedule'">
                                      <p v-if="post.success == 1">Successfully posted</p>
                                      <p v-else>Failed: {{post.error_msg}}</p>
                                    </div>

                                    <p v-if="postType=='schedule'">Next Schedule: <span>{{post.scheduled_on}}</span></p>
                                    <p v-if="postType=='schedule'">Recurring Type: <span>{{post.schedule_type.toUpperCase()}}</span></p>
                                    <p v-else>Executed on: <span>{{post.published_date | momentTime}}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </b-collapse>

        </b-card>
    </div>
    <div v-else>
        <p>You have no posts published or scheduled.</p>
    </div>
    <EditSchedule :scheduleData="currentScheduleData" :showModal="showScheduleModal" @hide-Modal="hideModal"/>
</div>
