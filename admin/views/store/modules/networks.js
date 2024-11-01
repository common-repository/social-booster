import Api from '@/utils/fetchWP'

const defaultState = {
    profiles: [],
    currentProfile: {
        'profile_name' : 'My Profile',
        'profile_id' : 'rx_' + Math.random().toString(36).substr(-7),
        'networks' : [],
    },
    toastMessage: '',
    activeTab: '',
    pinterest_boards : [],
    all_network : {},

};

const actions = {
    getAllProfiles: (context) => {
        Api().get('getAllProfile')
            .then(r => {
                context.commit('LOAD_ALL_PROFILES', r.data.profiles);
            })
            .catch((error) => {
                console.error(error);
            });
    },
    editNetworkStatus: (context, {id, checked}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        Api().post(`editNetworkStatus&id=${id}&checked=${checked}`)
            .then(r => {
                context.dispatch('getAllProfiles');
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Status updated', 'type': 'success'});
            })
            .catch((error) => {
                console.error(error);
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Failed to update', 'type': 'error'});
            });
      }
      else {
        Api().post(`editNetworkStatus?id=${id}&checked=${checked}`)
            .then(r => {
                context.dispatch('getAllProfiles');
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Status updated', 'type': 'success'});
            })
            .catch((error) => {
                console.error(error);
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Failed to update', 'type': 'error'});
            });
      }
    },

    createProfile: (context, {profile_id, profile_name}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let response = Api().post(`createProfile&profile_id=${profile_id}&profile_name=${profile_name}`)
              .then(r => {
                  return r.data
              })
              .catch((error) => {
                  console.error(error);
              });
          return response;
      }
      else {
        let response = Api().post(`createProfile?profile_id=${profile_id}&profile_name=${profile_name}`)
              .then(r => {
                  return r.data
              })
              .catch((error) => {
                  console.error(error);
              });
          return response;
      }
    },

    deleteNetwork: (context, {id}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        Api().delete(`deleteProfile&id=${id}`)
            .then(r => {
                context.dispatch('getAllProfiles');
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Network Deleted', 'type': 'success'});
            })
            .catch((error) => {
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Error', 'type': 'error'});
                console.error(error);
            });
      }
      else {
        Api().delete(`deleteProfile?id=${id}`)
            .then(r => {
                context.dispatch('getAllProfiles');
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Network Deleted', 'type': 'success'});
            })
            .catch((error) => {
                context.commit('FIRE_TOAST_MESSAGE', {'message': 'Error', 'type': 'error'});
                console.error(error);
            });
      }
    },
    deleteProfile: (context, {id}) => {
      if (window.rx_sb_obj.permalink_structure == "") {
        let response = Api().delete(`deleteMasterProfile&id=${id}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
      else {
        let response = Api().delete(`deleteMasterProfile?id=${id}`)
            .then(r => {
                return r.data;
            })
            .catch((error) => {
                console.error(error);
            });
        return response;
      }
    }
};

const mutations = {
    LOAD_ALL_PROFILES: (state, profiles) => {
        state.profiles = profiles;
        if(Object.keys(profiles).length > 0) {
            state.currentProfile = profiles[Object.keys(profiles)[0]];
            state.activeTab = Object.keys(profiles)[0];
        }
    },
    FIRE_TOAST_MESSAGE: (state, toastMessage) => {
        state.toastMessage = toastMessage;
    },
    LOAD_ALL_PINTEREST_BOARDS: (state, pinterest_boards) => {
        state.pinterest_boards = pinterest_boards;
    },
    SET_NEW_PROFILE: (state, newProfile) => {
      state.profiles = Object.assign({}, state.profiles, newProfile);
      state.currentProfile = newProfile[Object.keys(newProfile)[0]];
      state.activeTab = Object.keys(newProfile)[0];
    },
    ACTIVE_PROFILE: (state, profile_id) => {
        state.currentProfile = state.profiles[profile_id];
        state.activeTab = profile_id;
    },
};

const getters = {
    profiles: state => state.profiles,
    currentProfile: state => state.currentProfile,
    toastMessage: state => state.toastMessage,
    pinterest_boards: state => state.pinterest_boards,
    activeTab: state => state.activeTab,
};

const namespaced = true;

export default {
    namespaced,
    state: defaultState,
    getters,
    actions,
    mutations,
};
