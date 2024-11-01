import Api from '@/utils/fetchWP'
const defaultState = {
    startShare: false,
    shareToastMessage: ''
};

const actions = {
    instantShareFb: (context, {post_id, network_id, id, message, link}) => {
        context.commit('FIRE_START_SHARE', true);
        if (window.rx_sb_obj.permalink_structure == "") {
          let response = Api().post(`instantShareFb&post_id=${post_id}&network_id=${network_id}&id=${id}&message=${message}&link=${link}`)
              .then(r => {
                  context.dispatch('post/getAllInstantSharedPosts',null, {root:true});
                  context.commit('FIRE_START_SHARE', false);
                  return r.data;
              })
              .catch((error) => {
                  console.error(error);
                  context.commit('FIRE_START_SHARE', false);
              });
              return response;
        }
        else {
          let response = Api().post(`instantShareFb?post_id=${post_id}&network_id=${network_id}&id=${id}&message=${message}&link=${link}`)
              .then(r => {
                  context.dispatch('post/getAllInstantSharedPosts',null, {root:true});
                  context.commit('FIRE_START_SHARE', false);
                  return r.data;
              })
              .catch((error) => {
                  console.error(error);
                  context.commit('FIRE_START_SHARE', false);
              });
              return response;
        }
    },

    instantShareTb: (context, {post_id, network_id, id, message, link}) => {
      context.commit('FIRE_START_SHARE', true);
      if (window.rx_sb_obj.permalink_structure == "") {
        let response = Api().post(`instantShareTb&post_id=${post_id}&network_id=${network_id}&id=${id}&message=${message}&link=${link}`)
            .then(r => {
                context.dispatch('post/getAllInstantSharedPosts', null, {root: true});
                context.commit('FIRE_START_SHARE', false);
                return r.data;
            })
            .catch((error) => {
                context.commit('FIRE_START_SHARE', false);
                console.error(error);
            });
            return response;
      }
      else {
        let response = Api().post(`instantShareTb?post_id=${post_id}&network_id=${network_id}&id=${id}&message=${message}&link=${link}`)
            .then(r => {
                context.dispatch('post/getAllInstantSharedPosts', null, {root: true});
                context.commit('FIRE_START_SHARE', false);
                return r.data;
            })
            .catch((error) => {
                context.commit('FIRE_START_SHARE', false);
                console.error(error);
            });
            return response;
      }
    },

    instantShareTw: (context, {post_id, network_id, id, status, link}) => {
        context.commit('FIRE_START_SHARE', true);
        if (window.rx_sb_obj.permalink_structure == "") {
          let response = Api().post(`instantShareTw&post_id=${post_id}&network_id=${network_id}&id=${id}&status=${status}&link=${link}`)
              .then(r => {
                  context.dispatch('post/getAllInstantSharedPosts', null, {root: true});
                  context.commit('FIRE_START_SHARE', false);
                  return r.data;
              })
              .catch((error) => {
                  context.commit('FIRE_START_SHARE', false);
                  console.error(error);
              });
              return response;
        }
        else {
          let response = Api().post(`instantShareTw?post_id=${post_id}&network_id=${network_id}&id=${id}&status=${status}&link=${link}`)
              .then(r => {
                  context.dispatch('post/getAllInstantSharedPosts', null, {root: true});
                  context.commit('FIRE_START_SHARE', false);
                  return r.data;
              })
              .catch((error) => {
                  context.commit('FIRE_START_SHARE', false);
                  console.error(error);
              });
              return response;
        }
    },

    instantSharePt: (context, {post_id, network_id, id, status, link}) => {
        context.commit('FIRE_START_SHARE', true);
        if (window.rx_sb_obj.permalink_structure == "") {
          let response = Api().post(`instantSharePt&post_id=${post_id}&network_id=${network_id}&id=${id}&status=${status}&link=${link}`)
              .then(r => {
                  context.dispatch('post/getAllInstantSharedPosts', null, {root: true});
                  context.commit('FIRE_START_SHARE', false);
                  return r.data;
              })
              .catch((error) => {
                  context.commit('FIRE_START_SHARE', false);
                  console.error(error);
              });
          return response;
        }
        else {
          let response = Api().post(`instantSharePt?post_id=${post_id}&network_id=${network_id}&id=${id}&status=${status}&link=${link}`)
              .then(r => {
                  context.dispatch('post/getAllInstantSharedPosts', null, {root: true});
                  context.commit('FIRE_START_SHARE', false);
                  return r.data;
              })
              .catch((error) => {
                  context.commit('FIRE_START_SHARE', false);
                  console.error(error);
              });
          return response;
        }
    },
};

const mutations = {
    FIRE_TOAST_MESSAGE: (state, shareToastMessage) => {
        state.shareToastMessage = shareToastMessage;
    },
    FIRE_START_SHARE: (state, startShare) => {
        state.startShare = startShare;
    },
};

const getters = {
    startShare: state => state.startShare,
    shareToastMessage: state => state.shareToastMessage,
};

const namespaced = true;

export default {
    namespaced,
    state: defaultState,
    getters,
    actions,
    mutations,
};
