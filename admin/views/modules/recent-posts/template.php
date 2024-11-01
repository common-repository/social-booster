<div class="sb-recent-posts" id="sb-recent-posts">
    <div class="single-post"
            v-for="post in latestPosts"
            :key="post.id"
    >
        <div class="post">
            <div class="post-image pos-relative">
                <a :href="post.url"><img :src="post.img" alt="featured image"/></a>
            </div>
            <div class="post-content">
                <h1><a :href="post.url">{{post.title}}</a></h1>
                <p>{{post.excerpt}}</p>
                <ul class="texonomy">
                    <li>
                        <font-awesome-icon icon="user-circle" /> {{post.author}}</li>
                    <li>
                        <font-awesome-icon :icon="['far', 'calendar-alt']" /> {{post.post_date | momentDate}}</li>
                    <li>
                        <font-awesome-icon :icon="['far', 'clock']" /> {{post.post_date | momentTime }}</li>
                </ul>
            </div>
        </div>
        <div class="post-btn" style="display: inline-grid;">
            <a href="#" class="btn-default" style="margin-bottom:10px;" @click.prevent="showscheduleModal(post)">Schedule</a>
            <a href="#" class="btn-default" @click.prevent="showShareModal(post)"><?php _e('Post on social media','rx-sb'); ?></a>
        </div>
    </div>

    <SharePost :post="currentPost" :showModal="showPostModal" @hide-Modal = "hideModal"/>
    <SchedulePost :post="currentPost" :showModal="showschedulepost" @hide-Modal = "hideModal"/>

    <!--/single post-->
</div>
