import Api from '@/utils/fetchWP'

const defaultState = {
    totalPosts: 0,
    allPosts: [],
    allSchedulePosts: {},
    latestPosts: [],
    authors: [],
    categories: [],
    instantSharedPosts: {},
    sharedPosts: {},
    toastMessage: '',
    startUpdate: false,
    updateCompleteMessage: '',
    bitlyData:[],
};

const actions = {
    getTotalPosts: (context) => {
        Api().get('totalPosts')
            .then(r => {
                context.commit('TOTAL_POSTS', r.data.total);
            })
            .catch((error) => {
                console.error(error);
            });
    },
    getLatestPosts: (context) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        Api().get('loadPosts&limit=3')
            .then(r => {
                context.commit('LOAD_RECENT_POSTS', r.data.posts);
            })
            .catch((error) => {
                console.error(error);
            });
      }
      else {
        Api().get('loadPosts?limit=3')
            .then(r => {
                context.commit('LOAD_RECENT_POSTS', r.data.posts);
            })
            .catch((error) => {
                console.error(error);
            });
      }
    },
    getAllPosts: (context, {s, offset, author, cat, post_type, order}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let queryString = ``;
        let limit = 10;
        if (s) queryString += `&s=${s}`;
        if (author) queryString += `&author=${author}`;
        if (cat) queryString += `&cat=${cat}`;
        if (post_type) queryString += `&post_type=${post_type}`;
        if (order) queryString += `&order=${order}`;
        if (queryString) limit = -1;
        Api().get(`loadPosts&limit=${limit}&offset=${offset}${queryString}`)
            .then(r => {
                context.commit('LOAD_ALL_POSTS', r.data.posts);
            })
            .catch((error) => {
                console.error(error);
            });
      }
      else {
        let queryString = ``;
        let limit = 10;
        if (s) queryString += `&s=${s}`;
        if (author) queryString += `&author=${author}`;
        if (cat) queryString += `&cat=${cat}`;
        if (post_type) queryString += `&post_type=${post_type}`;
        if (order) queryString += `&order=${order}`;
        if (queryString) limit = -1;
        Api().get(`loadPosts?limit=${limit}&offset=${offset}${queryString}`)
            .then(r => {
                context.commit('LOAD_ALL_POSTS', r.data.posts);
            })
            .catch((error) => {
                console.error(error);
            });
      }
    },

    getAllSchedulePosts: (context) => {

        Api().get('allScheduledPosts')
            .then(r => {
                context.commit('LOAD_ALL_SCHEDULE_POSTS', Object.assign({}, r.data.posts));
            })
            .catch((error) => {
                console.error(error);
            });
    },

    getAllAuthors: (context) => {
        Api().get('getAllAuthors')
            .then(r => {
                context.commit('LOAD_ALL_AUTHORS', r.data.authors);
            })
            .catch((error) => {
                console.error(error);
            });
    },
    getAllCategories: (context) => {
        Api().get('getAllCategories')
            .then(r => {
                context.commit('LOAD_ALL_CATEGORIES', r.data.categories);
            })
            .catch((error) => {
                console.error(error);
            });
    },
    getAllInstantSharedPosts: (context) => {
        Api().get('loadInstantSharedPosts')
            .then(r => {
                context.commit('TOTAL_INSTANT_SHARED_POSTS', Object.assign({}, r.data.posts));
            })
            .catch((error) => {
                console.error(error);
            });
    },
    getAllSharedPosts: (context) => {
        Api().get('loadSharedPosts')
            .then(r => {
                context.commit('TOTAL_SHARED_POSTS', Object.assign({}, r.data.posts));
            })
            .catch((error) => {
                console.error(error);
            });
    },

    deleteSchedule: (context, {id}) => {
            let response = Api().delete(`ScheduleData/${id}`)
            .then(r => {
                context.dispatch('getAllSchedulePosts');
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Schedule Deleted', 'type': 'success'});
                return r.data;
            })
            .catch((error) => {
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Error', 'type': 'error'});
                console.error(error);
            });
            return response;
    },

    addSchedule: (context, {media, schedule, caption, post, date, time}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let queryString = ``;
        if (media) queryString += `media=${media}`;
        if (schedule) queryString += `&schedule=${schedule}`;
        if (caption) queryString += `&caption=${caption}`;
        if (post) queryString += `&post=${post}`;
        if (date) queryString += `&date=${date}`;
        if (time) queryString += `&time=${time}`;
        let response = Api().post(`addSchedule&${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
      else {
        let queryString = ``;
        if (media) queryString += `media=${media}`;
        if (schedule) queryString += `&schedule=${schedule}`;
        if (caption) queryString += `&caption=${caption}`;
        if (post) queryString += `&post=${post}`;
        if (date) queryString += `&date=${date}`;
        if (time) queryString += `&time=${time}`;
        let response = Api().post(`addSchedule?${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
    },

    addEditorialSchedule: (context, {media, schedule, caption, post, date, time, postTitle, postExcerpt}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let queryString = ``;
        if (media) queryString += `media=${media}`;
        if (schedule) queryString += `&schedule=${schedule}`;
        if (caption) queryString += `&caption=${caption}`;
        if (post) queryString += `&post=${post}`;
        if (date) queryString += `&date=${date}`;
        if (time) queryString += `&time=${time}`;
        if (postTitle) queryString += `&postTitle=${postTitle}`;
        if (postExcerpt) queryString += `&postExcerpt=${postExcerpt}`;
        let response = Api().post(`addEditorialSchedule&${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
      else {
        let queryString = ``;
        if (media) queryString += `media=${media}`;
        if (schedule) queryString += `&schedule=${schedule}`;
        if (caption) queryString += `&caption=${caption}`;
        if (post) queryString += `&post=${post}`;
        if (date) queryString += `&date=${date}`;
        if (time) queryString += `&time=${time}`;
        if (postTitle) queryString += `&postTitle=${postTitle}`;
        if (postExcerpt) queryString += `&postExcerpt=${postExcerpt}`;
        let response = Api().post(`addEditorialSchedule?${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
    },

    editSchedule: (context, {id, schedule, caption, date, time}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let queryString = ``;
        if (id) queryString += `id=${id}`;
        if (schedule) queryString += `&schedule=${schedule}`;
        if (caption) queryString += `&caption=${caption}`;
        if (date) queryString += `&date=${date}`;
        if (time) queryString += `&time=${time}`;
        context.commit('FIRE_START_SCHEDULE_UPDATE', true);
        let response = Api().post(`editSchedule&${queryString}`)
            .then(r => {
                context.dispatch('getAllSchedulePosts');
                context.commit('FIRE_START_SCHEDULE_UPDATE', false);
                context.commit('FIRE_UPDATE_TOAST_MESSAGE', {'message': 'Schedule Updated', 'type': 'success'});
            })
            .catch((error) => {
                context.commit('FIRE_UPDATE_TOAST_MESSAGE', {'message': 'Error', 'type': 'error'});
                context.commit('FIRE_START_SCHEDULE_UPDATE', false);
                console.error(error);
            });
        return response;
      }
      else {
        let queryString = ``;
        if (id) queryString += `id=${id}`;
        if (schedule) queryString += `&schedule=${schedule}`;
        if (caption) queryString += `&caption=${caption}`;
        if (date) queryString += `&date=${date}`;
        if (time) queryString += `&time=${time}`;
        context.commit('FIRE_START_SCHEDULE_UPDATE', true);
        let response = Api().post(`editSchedule?${queryString}`)
            .then(r => {
                context.dispatch('getAllSchedulePosts');
                context.commit('FIRE_START_SCHEDULE_UPDATE', false);
                context.commit('FIRE_UPDATE_TOAST_MESSAGE', {'message': 'Schedule Updated', 'type': 'success'});
            })
            .catch((error) => {
                context.commit('FIRE_UPDATE_TOAST_MESSAGE', {'message': 'Error', 'type': 'error'});
                context.commit('FIRE_START_SCHEDULE_UPDATE', false);
                console.error(error);
            });
        return response;
      }
    },

    bitlyData: (context) => {
        let response = Api().get('getBitly')
            .then(r => {
                return r.data
            })
            .catch((error) => {
                console.error(error);
            });
            return response;
    },
    savebitly: (context, {enabler, login, api_key}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let queryString = ``;
        if (enabler) queryString += `enabler=${enabler}`;
        if (login) queryString += `&login=${login}`;
        if (api_key) queryString += `&api_key=${api_key}`;

        let response = Api().post(`submitBitly&${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
      else {
        let queryString = ``;
        if (enabler) queryString += `enabler=${enabler}`;
        if (login) queryString += `&login=${login}`;
        if (api_key) queryString += `&api_key=${api_key}`;

        let response = Api().post(`submitBitly?${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
    },
    getPostTypeslist: (context) => {
        let response = Api().get('getPostTypeslist')
            .then(r => {
                return r.data
            })
            .catch((error) => {
                console.error(error);
            });
            return response;
    },
    submitPostTypeslist: (context, {data}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let queryString = ``;
        if (data) queryString += `data=${data}`;

        let response = Api().post(`submitPostTypeslist&${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
      else {
        let queryString = ``;
        if (data) queryString += `data=${data}`;

        let response = Api().post(`submitPostTypeslist?${queryString}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
    },
    getPostTypesSelected: (context) => {
        let response = Api().get('getPostTypesSelected')
            .then(r => {
                return r.data
            })
            .catch((error) => {
                console.error(error);
            });
            return response;
    },
};

const mutations = {
    TOTAL_POSTS: (state, totalPosts) => {
        state.totalPosts = totalPosts;
    },
    LOAD_RECENT_POSTS: (state, latestPosts) => {
        state.latestPosts = latestPosts;
    },
    LOAD_ALL_POSTS: (state, allPosts) => {
        state.allPosts = allPosts;
    },
    LOAD_ALL_SCHEDULE_POSTS: (state, allSchedulePosts) => {
        state.allSchedulePosts = allSchedulePosts;
    },
    LOAD_ALL_AUTHORS: (state, authors) => {
        state.authors = authors;
    },
    LOAD_ALL_CATEGORIES: (state, categories) => {
        state.categories = categories;
    },
    TOTAL_INSTANT_SHARED_POSTS: (state, posts) => {
        state.instantSharedPosts = posts;
    },
    TOTAL_SHARED_POSTS: (state, posts) => {
        state.sharedPosts = posts;
    },
    FIRE_TOAST_MESSAGE: (state, toastMessage) => {
        state.toastMessage = toastMessage;
    },
    FIRE_UPDATE_TOAST_MESSAGE: (state, updateCompleteMessage) => {
        state.updateCompleteMessage = updateCompleteMessage;
    },
    FIRE_START_SCHEDULE_UPDATE: (state, startUpdate) => {
        state.startUpdate = startUpdate;
    },
    BITLY_DATA: (state, bitlyData) => {
        state.bitlyData = bitlyData;
    },
};

const getters = {
    totalPosts: state => state.totalPosts,
    latestPosts: state => state.latestPosts,
    allPosts: state => state.allPosts,
    allSchedulePosts: state => state.allSchedulePosts,
    authors: state => state.authors,
    categories: state => state.categories,
    instantSharedPosts: state => state.instantSharedPosts,
    sharedPosts: state => state.sharedPosts,
    toastMessage: state => state.toastMessage,
    updateCompleteMessage: state => state.updateCompleteMessage,
    startUpdate: state => state.startUpdate,
    bitlyData: state => state.bitlyData,
};

const namespaced = true;

export default {
    namespaced,
    state: defaultState,
    getters,
    actions,
    mutations,
};
