<form action="#" @submit.prevent="searchFilter" method="post">
    <div class="sb-search-filter-content">
        <b-form-input type="search" placeholder="Search..." v-model="keyword"></b-form-input>
        <b-form-select class="author" v-model="authorsSelected" :options="authors"></b-form-select>
        <b-form-select class="categories" v-model="categoriesSelected" :options="categories"></b-form-select>
        <b-form-select class="post-type" v-model="postTypeSelected" :options="postType"></b-form-select>
        <b-form-select class="order-type" v-model="orderSelected" :options="orderType"></b-form-select>
        <button class="btn-default" type="submit"><font-awesome-icon icon="search" /></button>
    </div>
</form>
